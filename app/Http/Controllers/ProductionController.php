<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Penting untuk Transaksi
use Exception; // <-- Penting untuk error

class ProductionController extends Controller
{
    /**
     * Menampilkan halaman/form produksi.
     */
    public function index()
    {
        // Ambil semua produk untuk ditampilkan di dropdown
        $products = Product::orderBy('name')->get();
        return view('production.index', compact('products'));
    }

    /**
     * Menyimpan (memproses) produksi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::with('materials')->findOrFail($request->product_id); // Ambil produk & resepnya
        $quantityToMake = $request->quantity;

        // --- FASE 1: PENGECEKAN STOK ---
        // Kita cek dulu SEMUA bahan, sebelum mengubah apapun.
        foreach ($product->materials as $material) {
            $totalMaterialNeeded = $material->pivot->quantity_needed * $quantityToMake;

            if ($material->stock < $totalMaterialNeeded) {
                // Jika 1 bahan saja tidak cukup, gagalkan
                return back()->with('error', "Gagal! Stok '{$material->name}' tidak cukup. Hanya tersisa {$material->stock} {$material->unit}.");
            }
        }

        // --- FASE 2: EKSEKUSI (Pakai Transaksi Database) ---
        // Transaksi memastikan jika ada error di tengah jalan, semua perubahan dibatalkan.
        try {
            DB::transaction(function () use ($product, $quantityToMake) {

                // Kurangi stok setiap bahan baku
                foreach ($product->materials as $material) {
                    $totalMaterialNeeded = $material->pivot->quantity_needed * $quantityToMake;
                    $material->decrement('stock', $totalMaterialNeeded);
                }

                // Tambah stok produk jadi (blank)
                $product->increment('stock', $quantityToMake);

            });

            // Jika transaksi berhasil
            return back()->with('success', "Produksi berhasil! Stok '{$product->name}' telah ditambah sebanyak {$quantityToMake}.");

        } catch (Exception $e) {
            // Jika ada error saat transaksi
            return back()->with('error', "Terjadi error: " . $e->getMessage());
        }
    }
}