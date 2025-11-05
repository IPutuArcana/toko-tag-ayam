<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pesanan Baru (Kasir)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="customer_name" class="block font-medium text-sm text-gray-700">Nama Pelanggan (Opsional)</label>
                                <input type_text="text" name="customer_name" id="customer_name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('customer_name') }}">
                            </div>

                            <hr>
                            <h3 class="text-lg font-medium text-gray-900">Detail Pesanan</h3>
                            <p class="text-sm text-gray-600">Pilih produk dan masukkan jumlah serta teks kustom. Hanya produk dengan stok > 0 yang tampil.</p>

                            <div class="space-y-3">
                                @forelse ($products as $product)
                                    <div class="p-3 border rounded-md">
                                        <p class="font-semibold">{{ $product->name }} (Stok: {{ $product->stock }})</p>
                                        <p class="text-sm text-gray-600">Harga: Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>

                                        <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $product->id }}">

                                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="quantity_{{ $product->id }}" class="block text-sm font-medium text-gray-700">Jumlah Pesan</label>
                                                <input type_text="number" name="items[{{ $loop->index }}][quantity]" id="quantity_{{ $product->id }}"
                                                       class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                                       value="{{ old('items.'.$loop->index.'.quantity', 0) }}" min="0" max="{{ $product->stock }}">
                                            </div>
                                            <div>
                                                <label for="custom_text_{{ $product->id }}" class="block text-sm font-medium text-gray-700">Teks Kustom (Ukiran)</label>
                                                <input type_text="text" name="items[{{ $loop->index }}][custom_text]" id="custom_text_{{ $product->id }}"
                                                       class="block mt-1 w-full rounded-md shadow-sm border-gray-300"
                                                       value="{{ old('items.'.$loop->index.'.custom_text') }}" placeholder="Contoh: Ayam_001 - Ayam_050">
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-red-500">Tidak ada produk (blank) yang siap dijual. Silakan lakukan "Produksi" terlebih dahulu.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-500 active:bg-brand-700 focus:outline-none focus:border-brand-700 focus:ring ring-brand-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Buat Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>