<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Produk Blank Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            <strong>Oops! Ada yang salah:</strong>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Nama Produk</label>
                                <input type="text" name="name" id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('name') }}" required>
                            </div>
                            
                            <div>
                                <label for="category_id" class="block font-medium text-sm text-gray-700">Kategori</label>
                                <select name="category_id" id="category_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="selling_price" class="block font-medium text-sm text-gray-700">Harga Jual (Rp)</label>
                                <input type_text="number" name="selling_price" id="selling_price" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('selling_price', 0) }}" step="0.01" required>
                            </div>

                            <div>
                                <label for="stock" class="block font-medium text-sm text-gray-700">Stok Awal (Blank)</label>
                                <input type_text="number" name="stock" id="stock" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('stock', 0) }}" required>
                            </div>

                            <div class="md:col-span-2">
                                <div class="md:col-span-2">
                                    <label for="image" class="block font-medium text-sm text-gray-700">Gambar Produk</label>
                                    <input type="file" name="image" id="image" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm p-2 border">
                                </div>

                                    <div class="md:col-span-2">
                                <label for="description" class="block font-medium text-sm text-gray-700">Deskripsi</label>
                                <textarea name="description" id="description" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">{{ old('description') }}</textarea>
                            </div>

                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Resep (Bahan Baku yang Dibutuhkan)</h3>
                                <div class="space-y-2">
                                    @forelse ($materials as $material)
                                        <div class="flex items-center space-x-4">
                                            <label for="material_{{ $material->id }}" class="flex-1">
                                                {{ $material->name }} (per {{ $material->unit }})
                                            </label>
                                            <input type_hidden="hidden" name="materials[{{ $loop->index }}][id]" value="{{ $material->id }}">
                                            <input type_text="number" name="materials[{{ $loop->index }}][quantity]" id="material_{{ $material->id }}" 
                                                   class="block w-32 rounded-md shadow-sm border-gray-300" 
                                                   value="{{ old('materials.'.$loop->index.'.quantity', 0) }}" 
                                                   step="0.01" min="0">
                                        </div>
                                    @empty
                                        <p class="text-gray-500">Silakan tambahkan Bahan Baku terlebih dahulu.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('products.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-500 active:bg-brand-700 focus:outline-none focus:border-brand-700 focus:ring ring-brand-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Simpan Produk
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>