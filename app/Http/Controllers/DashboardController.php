<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan statistik.
     */
    public function index()
    {
        // 1. Ambil jumlah pesanan yang masih 'pending'
        $pendingOrders = Order::where('status', 'pending')->count();

        // 2. Ambil total stok semua produk blank
        $totalProductStock = Product::sum('stock');

        // Kirim semua data ini ke view
        return view('dashboard', [
            'pendingOrders' => $pendingOrders,
            'totalProductStock' => $totalProductStock,
        ]);
    }
}