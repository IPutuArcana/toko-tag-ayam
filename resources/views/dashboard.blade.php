<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <<div class="p-6 text-gray-900">
                    <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Saat Ini</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                        <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-4 shadow-sm">
                            <div class="text-sm font-medium text-yellow-800">Pesanan Pending</div>
                            <div class="text-3xl font-bold text-yellow-900 mt-1">
                                {{ $pendingOrders }}
                            </div>
                        </div>

                        <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 shadow-sm">
                            <div class="text-sm font-medium text-gray-800">Total Stok Produk Blank</div>
                            <div class="text-3xl font-bold text-gray-900 mt-1">
                                {{ $totalProductStock }}
                            </div>
                        </div>

                        <div class="bg-red-100 border border-red-300 rounded-lg p-4 shadow-sm">
                            <div class="text-sm font-medium text-red-800">Bahan Baku Menipis (<10)</div>
                            <div class="text-3xl font-bold text-red-900 mt-1">
                                {{ $lowStockMaterials }}
                            </div>
                        </div>

                    </div>
                    
                    <h3 class="text-lg font-medium mb-4">Aksi Cepat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <a href="{{ route('orders.create') }}" class="block p-6 bg-brand-600 text-white rounded-lg shadow hover:bg-brand-700 transition">
                            <h4 class="font-semibold text-xl">Kasir</h4>
                            <p>Buat pesanan kustom baru.</p>
                        </a>

                        <a href="{{ route('orders.index') }}" class="block p-6 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                            <h4 class="font-semibold text-xl">Antrian Pesanan</h4>
                            <p>Lihat semua pesanan yang sedang diproses.</p>
                        </a>

                        @if(Auth::user()->role == 'admin')
                        <a href="{{ route('products.index') }}" class="block p-6 bg-gray-700 text-white rounded-lg shadow hover:bg-gray-800 transition">
                            <h4 class="font-semibold text-xl">Manajemen Produk</h4>
                            <p>Atur stok dan resep produk blank.</p>
                        </a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
