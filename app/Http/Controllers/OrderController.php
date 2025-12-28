<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan (Antrian).
     */
    public function index(Request $request) // Tambahkan Request untuk Pencarian
    {
        $search = $request->input('search');

        $query = Order::query();

        $query->when($search, function ($q) use ($search) {
            return $q->where('customer_name', 'like', '%' . $search . '%')
                     ->orWhere('id', 'like', '%' . $search . '%');
        });

        $orders = $query->with('user')
                       ->orderByRaw("FIELD(status, 'pending', 'in_production', 'completed', 'paid')")
                       ->latest()
                       ->paginate(15)
                       ->appends($request->query());

        return view('orders.index', compact('orders', 'search'));
    }

    /**
     * Menampilkan form kasir untuk membuat pesanan baru.
     */
    public function create()
    {
        // Hanya tampilkan produk (blank) yang stoknya ada
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        return view('orders.create', compact('products'));
    }

    /**
     * Menyimpan pesanan baru (Status 'pending').
     */
    public function store(Request $request)
    {
        // PERBAIKAN 1: Validasi diubah
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            
            // UBAH DARI 'min:1' MENJADI 'nullable|integer|min:0'
            // Kita izinkan 0 agar item yang tidak dipilih tidak bikin error
            'items.*.quantity' => 'nullable|integer|min:0', 
            
            'items.*.custom_text' => 'nullable|string',
        ]);

        $totalPrice = 0;
        $itemsData = [];

        // --- FASE 1: Validasi Stok & Hitung Harga ---
        foreach ($request->items as $item) {
            // Logika ini sudah BENAR, dia akan menyaring item yang quantity-nya > 0
            // Item dengan quantity 0 atau kosong akan diabaikan (skip)
            if (!empty($item['quantity']) && $item['quantity'] > 0) {
                
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    return back()->with('error', "Stok '{$product->name}' tidak cukup! Sisa stok: {$product->stock}")->withInput();
                }

                $itemPrice = $product->selling_price * $item['quantity'];
                $totalPrice += $itemPrice;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price_at_time_of_sale' => $product->selling_price,
                    'custom_text' => $item['custom_text'] ?? null, // Pakai null coalescing operator biar aman
                ];
            }
        }

        // PERBAIKAN 2: Pastikan setelah disaring, ada minimal 1 barang yang valid
        if (empty($itemsData)) {
            return back()->with('error', "Anda belum memilih produk satupun (Jumlah minimal 1).")->withInput();
        }

        // --- FASE 2: Simpan ke Database (Transaksi) ---
        try {
            DB::transaction(function () use ($request, $totalPrice, $itemsData) {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'customer_name' => $request->customer_name,
                    'status' => 'pending',
                    'total_price' => $totalPrice,
                ]);

                $order->items()->createMany($itemsData);
            });

            return redirect()->route('orders.index')->with('success', 'Pesanan baru berhasil dibuat!');

        } catch (Exception $e) {
            return back()->with('error', "Terjadi error: " . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show(Order $order)
    {
        $order->load('user', 'items.product');
        return view('orders.show', compact('order'));
    }

    /**
     * [KHUSUS ADMIN] Update status: pending -> in_production -> completed
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:in_production,completed',
        ]);

        $newStatus = $request->status;

        if ($newStatus == 'completed' && $order->status != 'completed') {
            try {
                DB::transaction(function () use ($order) {
                    foreach ($order->items as $item) {
                        $product = $item->product; 

                        if ($product->stock < $item->quantity) {
                            throw new Exception("Gagal! Stok '{$product->name}' tidak cukup. Sisa stok: {$product->stock}");
                        }
                        $product->decrement('stock', $item->quantity);
                    }
                    $order->update(['status' => 'completed']);
                });

                return back()->with('success', 'Status pesanan diubah ke "Completed" dan stok telah dikurangi.');

            } catch (Exception $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        $order->update(['status' => $newStatus]);
        return back()->with('success', "Status pesanan diubah ke '{$newStatus}'.");
    }

    // Example inside OrderController.php

    public function markAsPaid(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // 1. Update Order Status
        $order->status = 'paid';
        $order->save();

        // 2. RECORD THE FINANCIAL TRANSACTION (The Commercial Step)
        // Check if transaction already exists to avoid duplicates
        if (!$order->transaction) {
            \App\Models\Transaction::create([
                'type' => 'income', // Because it's a sale
                'amount' => $order->total_price, // Assuming you have this field
                'description' => 'Payment for Order #' . $order->id,
                'order_id' => $order->id,
                'transaction_date' => now(),
            ]);
        }

        return response()->json(['message' => 'Order Paid and Recorded in Ledger'], 200);
    }
     // ... (fungsi markAsPaid() Anda ada di sini) ...

    /**
     * Export data pesanan ke file CSV.
     */
    public function exportCsv()
    {
        $fileName = 'laporan-pesanan.csv';
        $orders = Order::with('user', 'items.product')->get(); // Ambil semua data

        // Siapkan header untuk browser agar tahu ini file download
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // Kolom header di file CSV
        $columns = [
            'ID Pesanan', 
            'Pelanggan', 
            'Status', 
            'Total Harga', 
            'Kasir', 
            'Tgl Pesan', 
            'Item Dipesan', 
            'Teks Kustom'
        ];

        // Buat file CSV di "memory" dan kirim ke browser
        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w'); // Buka output stream
            fputcsv($file, $columns); // Tulis header

            // Tulis data baris per baris
            foreach ($orders as $order) {
                // Gabungkan item & teks kustom (sama seperti di export class kita)
                $itemsString = $order->items->map(function ($item) {
                    return $item->quantity . 'x ' . ($item->product->name ?? 'Produk Dihapus');
                })->implode('; ');

                $customTextString = $order->items->map(function ($item) {
                    return $item->custom_text;
                })->implode('; ');

                $row['ID']  = $order->id;
                $row['Pelanggan'] = $order->customer_name;
                $row['Status'] = $order->status;
                $row['Total'] = $order->total_price;
                $row['Kasir'] = $order->user->name;
                $row['Tanggal'] = $order->created_at->format('Y-m-d H:i:s');
                $row['Items'] = $itemsString;
                $row['Kustom'] = $customTextString;

                fputcsv($file, [
                    $row['ID'], 
                    $row['Pelanggan'], 
                    $row['Status'], 
                    $row['Total'], 
                    $row['Kasir'], 
                    $row['Tanggal'], 
                    $row['Items'], 
                    $row['Kustom']
                ]);
            }

            fclose($file); // Tutup stream
        };

        // Kembalikan response sebagai file download
        return response()->stream($callback, 200, $headers);
    }
}