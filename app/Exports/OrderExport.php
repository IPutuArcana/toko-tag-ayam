<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil semua data pesanan dengan relasinya
        return Order::with('user', 'items.product')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Ini adalah nama-nama kolom di file Excel
        return [
            'ID Pesanan',
            'Pelanggan',
            'Status',
            'Total Harga',
            'Kasir',
            'Tgl Pesan',
            'Item Dipesan',
            'Teks Kustom',
        ];
    }

    /**
     * @param mixed $order
     *
     * @return array
     */
    public function map($order): array
    {
        // Ini mengubah data dari collection menjadi baris di Excel
        
        // Gabungkan semua item dan teks kustom menjadi satu string
        $itemsString = $order->items->map(function ($item) {
            return $item->quantity . 'x ' . ($item->product->name ?? 'Produk Dihapus');
        })->implode('; '); // Gabung dengan titik koma

        $customTextString = $order->items->map(function ($item) {
            return $item->custom_text;
        })->implode('; '); // Gabung dengan titik koma

        return [
            $order->id,
            $order->customer_name,
            $order->status,
            $order->total_price,
            $order->user->name,
            $order->created_at->format('Y-m-d H:i:s'), // Format tanggal
            $itemsString,
            $customTextString,
        ];
    }
}