<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catatan Pengeluaran Fasilitas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Daftar Pengeluaran Piai Futsal Fitness</h3>
                
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Nama Item</th>
                                <th class="px-6 py-3">Jumlah (Rp)</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $expense->item_name }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $expense->expense_date }}</td>
                                <td class="px-6 py-4">{{ ucfirst($expense->category) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center">Belum ada data pengeluaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>