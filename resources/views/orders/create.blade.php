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
                        
                        <div class="mb-8 bg-gray-50 p-4 rounded-lg border">
                            <label for="customer_name" class="block font-bold text-lg text-gray-700 mb-2">Nama Pelanggan</label>
                            <input type="text" name="customer_name" id="customer_name" 
                                   class="block w-full md:w-1/2 rounded-md shadow-sm border-gray-300 focus:border-brand-500 focus:ring focus:ring-brand-200" 
                                   value="{{ old('customer_name') }}" placeholder="Masukkan nama pemesan...">
                        </div>

                        <hr class="mb-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Pilih Produk</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse ($products as $product)
                                <div class="border rounded-lg shadow-sm hover:shadow-md transition duration-200 overflow-hidden flex flex-col h-full bg-white">
                                    
                                    <div class="h-48 w-full bg-gray-200 relative">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm16.5-1.5H3.75V6H20.25v12Z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute top-2 right-2 bg-white/90 backdrop-blur px-2 py-1 rounded text-xs font-bold shadow">
                                            Stok: {{ $product->stock }}
                                        </div>
                                    </div>

                                    <div class="p-4 flex-1 flex flex-col">
                                        <h4 class="font-bold text-lg text-gray-800">{{ $product->name }}</h4>
                                        <p class="text-brand-600 font-bold text-xl my-2">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                                        <p class="text-sm text-gray-500 mb-4 flex-1">{{ Str::limit($product->description, 60) }}</p>
                                        
                                        <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $product->id }}">
                                        
                                        <div class="space-y-3 mt-auto pt-4 border-t">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah</label>
                                                <input type="number" name="items[{{ $loop->index }}][quantity]" 
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-200"
                                                       value="{{ old('items.'.$loop->index.'.quantity', 0) }}" min="0" max="{{ $product->stock }}">
                                            </div>
                                            
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Ukiran / Teks Kustom</label>
                                                <input type="text" name="items[{{ $loop->index }}][custom_text]" 
                                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring focus:ring-brand-200 text-sm"
                                                       value="{{ old('items.'.$loop->index.'.custom_text') }}" placeholder="Contoh: Ayam 01-10">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-3 text-center py-10">
                                    <p class="text-gray-500 text-lg">Belum ada produk yang tersedia. Silakan lakukan produksi terlebih dahulu.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="fixed bottom-0 left-0 right-0 bg-white border-t p-4 shadow-lg md:relative md:bg-transparent md:border-none md:shadow-none md:p-0 md:mt-8">
                            <div class="max-w-7xl mx-auto flex justify-end items-center">
                                <div class="mr-4 text-sm text-gray-500 hidden md:block">
                                    Pastikan data pesanan sudah benar sebelum disimpan.
                                </div>
                                <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-6 py-3 bg-brand-600 border border-transparent rounded-lg font-bold text-white uppercase tracking-widest hover:bg-brand-500 active:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Proses Pesanan
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="h-20 md:h-0"></div>
</x-app-layout>