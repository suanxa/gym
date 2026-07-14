<x-app-layout>
    {{-- FORCE GLOBAL STYLE UNTUK SINKRONISASI TEMA TERANG/GELAP --}}
    <style>
        .dark body, .dark main, .dark .min-h-screen { 
            background-color: #030712 !important; 
        }
        body, main, .min-h-screen { 
            background-color: #f3f4f6 !important; 
            transition: background-color 0.2s ease;
        }
    </style>

    <div class="p-4 sm:ml-1 bg-gray-100 dark:bg-gray-950 min-h-screen pt-2 text-gray-800 dark:text-gray-100 transition-colors duration-200" x-data="{ openModal: false }">
        <div class="max-w-7xl mx-auto space-y-6">
            
            {{-- HEADER MODUL --}}
            <div class="p-6 bg-gradient-to-r from-white via-slate-50 to-indigo-50 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/40 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black tracking-wide text-gray-900 dark:text-white uppercase">
                        Catatan <span class="text-indigo-600 dark:text-indigo-400">Pengeluaran</span>
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitoring operasional dan biaya pemeliharaan fasilitas Piai Futsal Fitness.</p>
                </div>
                <button @click="openModal = true" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-5 rounded-xl transition shadow-md shadow-indigo-600/20 text-xs uppercase tracking-wide">
                    + Tambah Item Pengeluaran
                </button>
            </div>

            @if(session('success'))
                <div class="p-4 text-sm text-green-800 dark:text-green-400 rounded-xl bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900/50 font-bold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- TABEL DATA --}}
            <div class="bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm dark:shadow-xl overflow-hidden backdrop-blur-sm">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-950/60 border-b border-gray-200 dark:border-gray-800 tracking-wider">
                            <tr>
                                <th class="px-6 py-4 w-12 text-center">No</th>
                                <th class="px-6 py-4">Nama Item</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Jumlah (Rp)</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Catatan</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800/60 font-medium">
                            @forelse($expenses as $index => $expense)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-900/40 transition-colors duration-150">
                                <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $expense->item_name }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider 
                                        {{ $expense->category == 'equipment' ? 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200/60' : '' }}
                                        {{ $expense->category == 'maintenance' ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-200/60' : '' }}
                                        {{ $expense->category == 'utility' ? 'bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-200/60' : '' }}
                                        {{ $expense->category == 'other' ? 'bg-gray-50 dark:bg-gray-700/40 text-gray-600 dark:text-gray-300 border border-gray-200' : '' }}">
                                        {{ strtoupper($expense->category) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-black font-mono text-gray-900 dark:text-white">Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-xs font-mono text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($expense->expense_date)->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4 text-xs text-gray-400 dark:text-gray-500 italic">{{ $expense->note ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 font-bold text-xs uppercase tracking-tighter">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center italic text-gray-400">Belum ada data pengeluaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MODAL TAMBAH (DESAIN MODERN) --}}
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm" @click="openModal = false"></div>
            <div class="relative bg-white dark:bg-gray-950 rounded-3xl shadow-2xl w-full max-w-lg p-8 border border-gray-200 dark:border-gray-800" @click.away="openModal = false">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wider">Tambah Pengeluaran</h3>
                    <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
                </div>
                <form action="{{ route('admin.expenses.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-500 dark:text-gray-400 mb-1">Nama Item</label>
                        <input type="text" name="item_name" required class="w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-3 text-sm focus:ring-2 focus:ring-indigo-500 dark:text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-500 dark:text-gray-400 mb-1">Kategori</label>
                            <select name="category" class="w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-3 text-sm dark:text-white">
                                <option value="equipment">Equipment</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="utility">Utility</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-500 dark:text-gray-400 mb-1">Tanggal</label>
                            <input type="date" name="expense_date" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-3 text-sm dark:text-white font-mono">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-500 dark:text-gray-400 mb-1">Jumlah Biaya (Rp)</label>
                        <input type="number" name="amount" required class="w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-3 text-sm dark:text-white font-mono font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-gray-500 dark:text-gray-400 mb-1">Catatan</label>
                        <textarea name="note" rows="2" class="w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 p-3 text-sm dark:text-white"></textarea>
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 rounded-xl uppercase text-xs tracking-wider">Simpan</button>
                        <button type="button" @click="openModal = false" class="px-5 bg-gray-100 dark:bg-gray-900 text-gray-500 font-bold rounded-xl text-xs uppercase">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>