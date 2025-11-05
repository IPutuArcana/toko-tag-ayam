<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pesanan #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Aksi Pesanan</h3>
                    <div class="mt-4 flex flex-wrap gap-4">

                        @if(Auth::user()->role == 'admin')
                            @if($order->status == 'pending')
                                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type_hidden="hidden" name="status" value="in_production">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm">Mulai Produksi (Ukiran)</button>
                                </form>
                            @endif
                            @if($order->status == 'in_production')
                                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menyelesaikan pesanan ini? Stok akan dikurangi.');">
                                    @csrf @method('PATCH')
                                    <input type_hidden="hidden" name="status" value="completed">
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm">Selesaikan Produksi</button>
                                </form>
                            @endif
                        @endif

                        @if($order->status == 'completed')
                            <form action="{{ route('orders.pay', $order->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-emerald-500 text-white rounded-md text-sm">Tandai Sudah Lunas</button>
                            </form>
                        @endif

                        @if($order->status == 'paid')
                            <span class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium">PESANAN LUNAS</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="font-semibold">ID Pesanan:</h3>
                            <p>#{{ $order->id }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold">Status:</h3>
                            <p class="font-bold uppercase">{{ $order->status }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold">Nama Pelanggan:</h3>
                            <p>{{ $order->customer_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold">Dicatat Oleh Kasir:</h3>
                            <p>{{ $order->user->name ?? 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="font-semibold">Total Tagihan:</h3>
                            <p class="text-2xl font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <hr class="my-6">

                    <h3 class="text-xl font-semibold mb-4">Item Pesanan</h3>
                    <div class="space-y-4">
                        @foreach ($order->items as $item)
                            <div class="border rounded-md p-4">
                                <p class="font-bold text-lg">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                                <p>Jumlah: <span class="font-semibold">{{ $item->quantity }}</span></p>
                                <p>Harga Satuan: <span class="font-semibold">Rp {{ number_format($item->price_at_time_of_sale, 0, ',', '.') }}</span></p>
                                <div class="mt-2 p-2 bg-gray-50 rounded">
                                    <p class="text-sm font-medium">Teks Kustom (Ukiran):</p>
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $item->custom_text ?? '(Tidak ada teks kustom)' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>