<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Bahan Baku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('materials.update', $material->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700">Nama Bahan Baku</label>
                            <input type_text="text" name="name" id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('name', $material->name) }}" required>
                        </div>

                        <div class="mt-4">
                            <label for="stock" class="block font-medium text-sm text-gray-700">Stok</label>
                            <input type_text="number" name="stock" id="stock" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('stock', $material->stock) }}" required>
                        </div>
                        
                        <div class="mt-4">
                            <label for="unit" class="block font-medium text-sm text-gray-700">Satuan (Unit)</label>
                            <input type_text="text" name="unit" id="unit" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('unit', $material->unit) }}" placeholder="Contoh: lembar, pcs, meter" required>
                        </div>

                        <div class="mt-4">
                            <label for="cost_price" class="block font-medium text-sm text-gray-700">Harga Modal (per Unit)</Glabel>
                            <input type_text="number" name="cost_price" id="cost_price" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" value="{{ old('cost_price', $material->cost_price) }}" step="0.01" required>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('materials.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-brand-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-500 active:bg-brand-700 focus:outline-none focus:border-brand-700 focus:ring ring-brand-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Update
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>