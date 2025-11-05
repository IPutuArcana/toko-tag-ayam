<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fitur Produksi Blank Tag') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4 text-gray-600">
                        Gunakan halaman ini untuk mencatat produksi tag kosongan. Sistem akan otomatis mengurangi stok bahan baku sesuai resep dan menambah stok produk blank.
                    </p>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
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

                    <form action="{{ route('production.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="product_id" class="block font-medium text-sm text-gray-700">Produk yang Akan Dibuat</label>
                                <select name="product_id" id="product_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (Stok saat ini: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="quantity" class="block font-medium text-sm text-gray-700">Jumlah yang Dibuat</label>
                                <input type_text="number" name="quantity" id="quantity" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('quantity', 1) }}" min="1" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-500 active:bg-brand-700 focus:outline-none focus:border-brand-700 focus:ring ring-brand-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Proses Produksi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>