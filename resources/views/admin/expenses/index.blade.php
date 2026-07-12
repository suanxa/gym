<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Catatan Pengeluaran Fasilitas') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Flash Message Notifikasi --}}
            @if(session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Pengeluaran Piai Futsal Fitness</h3>
                    {{-- Tombol Tambah --}}
                    <button @click="openModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl text-xs uppercase shadow transition">
                        + Tambah Pengeluaran
                    </button>
                </div>
                
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Nama Item</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3">Jumlah (Rp)</th>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Catatan</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $expense)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $expense->item_name }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-[10px] font-bold 
                                        {{ $expense->category == 'equipment' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $expense->category == 'maintenance' ? 'bg-orange-100 text-orange-700' : '' }}
                                        {{ $expense->category == 'utility' ? 'bg-purple-100 text-purple-700' : '' }}
                                        {{ $expense->category == 'other' ? 'bg-gray-100 text-gray-700' : '' }}
                                    ">
                                        {{ strtoupper($expense->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-800">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-xs">{{ \Carbon\Carbon::parse($expense->expense_date)->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4 text-xs text-gray-400">{{ $expense->note ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    {{-- Form Hapus --}}
                                    <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan pengeluaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold text-xs uppercase tracking-tighter">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center italic text-gray-400">Belum ada data pengeluaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MODAL TAMBAH PENGELUARAN (ALPINU.JS) --}}
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen p-4 text-center">
                <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="openModal = false"></div>

                <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full p-6" @click.away="openModal = false">
                    <div class="flex justify-between items-center border-b pb-3 mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Tambah Catatan Pengeluaran</h3>
                        <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
                    </div>

                    <form action="{{ route('admin.expenses.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Nama Item / Pengeluaran</label>
                                <input type="text" name="item_name" required placeholder="Contoh: Service AC, Beli Dumbbell Baru" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Kategori</label>
                                    <select name="category" required class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <option value="equipment">Equipment</option>
                                        <option value="maintenance">Maintenance</option>
                                        <option value="utility">Utility</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Tanggal</label>
                                    <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Jumlah Biaya (Rp)</label>
                                <input type="number" name="amount" required placeholder="Contoh: 150000" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-600 mb-1">Catatan Tambahan (Opsional)</label>
                                <textarea name="note" rows="3" placeholder="Keterangan opsional..." class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                            <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-100 text-gray-500 rounded-xl hover:bg-gray-200 text-xs font-bold uppercase">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 text-xs font-bold uppercase shadow">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>