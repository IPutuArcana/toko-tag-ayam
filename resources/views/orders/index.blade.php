<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Antrian Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <a href="{{ route('orders.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-4">
                        + Buat Pesanan Baru (Kasir)
                    </a>

                    <form action="{{ route('orders.index') }}" method="GET" class="mb-4 mt-4">
                        <div class="flex">
                            <input type_text="text" name="search" class="block w-full rounded-l-md shadow-sm border-gray-300"
                                   placeholder="Cari berdasarkan Nama Pelanggan atau ID Pesanan..."
                                   value="{{ $search ?? '' }}">

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Cari
                            </button>

                            @if(isset($search) && $search)
                            <a href="{{ route('orders.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>

                    @if(Auth::user()->role == 'admin')
                        <div class="mb-4">
                            <a href="{{ route('orders.export.csv') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900">
                                Download Laporan (CSV)
                            </a>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">#{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->customer_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $badgeClass = '';
                                            if ($order->status == 'pending') {
                                                $badgeClass = 'bg-yellow-100 text-yellow-800'; // Kuning muda
                                            } elseif ($order->status == 'in_production') {
                                                $badgeClass = 'bg-blue-500 text-white'; // Biru solid
                                            } elseif ($order->status == 'completed') {
                                                $badgeClass = 'bg-green-500 text-white'; // Hijau solid
                                            } elseif ($order->status == 'paid') {
                                                $badgeClass = 'bg-gray-700 text-white'; // Abu-abu tua
                                            }
                                        @endphp

                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md {{ $badgeClass }}">
                                            {{ str_replace('_', ' ', $order->status) }} </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        Belum ada pesanan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>