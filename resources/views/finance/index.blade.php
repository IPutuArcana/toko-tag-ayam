<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Keuangan & Kas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Pemasukan</div>
                    <div class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Pengeluaran</div>
                    <div class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">Keuntungan Bersih (Profit)</div>
                    <div class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-1">
                        Rp {{ number_format($netProfit, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Catat Pengeluaran</h3>
                        
                        @if(session('success'))
                            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('finance.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <input type="text" name="description" placeholder="Contoh: Beli Gas, Plastik, Ayam" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Nominal (Rp)</label>
                                <input type="number" name="amount" placeholder="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>

                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Pengeluaran
                            </button>
                        </form>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Transaksi</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($transactions as $trx)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                            {{ $trx->description }}
                                            @if($trx->order_id)
                                                <a href="{{ route('orders.show', $trx->order_id) }}" class="text-blue-500 text-xs hover:underline">#{{ $trx->order_id }}</a>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm">
                                            @if($trx->type == 'income')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Pemasukan</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Pengeluaran</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-right font-medium {{ $trx->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $trx->type == 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>