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

    {{-- [FIXED MARGIN & RESPONSIVE DESIGN] --}}
    <div class="p-4 sm:ml-1 bg-gray-100 dark:bg-gray-950 min-h-screen pt-2 text-gray-800 dark:text-gray-100 transition-colors duration-200" x-data="{ showForm: false }">
        <div class="max-w-7xl mx-auto space-y-6">
            
            {{-- HEADER BANNER MODUL --}}
            <div class="p-6 bg-gradient-to-r from-white via-slate-50 to-red-50 dark:from-gray-950 dark:via-gray-900 dark:to-red-950/40 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black tracking-wide text-gray-900 dark:text-white uppercase">
                        Sistem <span class="text-red-600 dark:text-red-500">Kasir & Transaksi</span>
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pencatatan kas masuk instan, monitoring iuran anggota, dan validasi transaksi luar.</p>
                </div>
                
                {{-- BUTTON TRIGGER FORM GUEST --}}
                <button @click="showForm = !showForm" 
                        class="w-full sm:w-auto inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-black py-3 px-5 rounded-xl transition duration-150 shadow-md shadow-red-600/10 text-xs uppercase tracking-wide">
                    <span x-text="showForm ? '✕ Tutup Form' : '＋ Input Transaksi Guest'"></span>
                </button>
            </div>

            {{-- NOTIFIKASI SYSTEM --}}
            @if(session('success'))
                <div class="p-4 text-sm text-green-800 dark:text-green-400 rounded-xl bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900/50 font-bold shadow-sm">
                    <span class="flex items-center gap-2"><i class="w-2 h-2 rounded-full bg-green-500"></i> {{ session('success') }}</span>
                </div>
            @endif

            {{-- FORM INPUT GUEST DYNAMIC INTERFACE --}}
            <div x-show="showForm" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 p-6 rounded-2xl shadow-sm dark:shadow-xl backdrop-blur-sm" x-cloak>
                
                <h4 class="font-black text-gray-900 dark:text-white mb-5 text-sm uppercase tracking-wide flex items-center gap-2 border-b border-gray-100 dark:border-gray-800 pb-3">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Pencatatan Transaksi Luar (Non-Member)
                </h4>
                
                <form action="{{ route('admin.payments.store_manual') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block mb-2 text-xs font-black uppercase text-gray-500 dark:text-gray-400 tracking-wider">Nama Pelanggan / Team</label>
                            <input type="text" name="external_customer_name" class="bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 text-gray-900 dark:text-white text-sm rounded-xl block w-full p-3 focus:ring-2 focus:ring-red-500 focus:border-transparent focus:outline-none transition font-semibold" placeholder="Nama pengunjung..." required>
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-black uppercase text-gray-500 dark:text-gray-400 tracking-wider">Jumlah Bayar (Rp)</label>
                            <input type="number" name="amount" class="bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 text-gray-900 dark:text-white text-sm rounded-xl block w-full p-3 focus:ring-2 focus:ring-red-500 focus:border-transparent focus:outline-none transition font-mono font-bold" placeholder="50000" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-black uppercase text-gray-500 dark:text-gray-400 tracking-wider">Keterangan Layanan</label>
                            <select name="description" class="bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 text-gray-900 dark:text-white text-sm rounded-xl block w-full p-3 focus:ring-2 focus:ring-red-500 focus:outline-none font-semibold" required>
                                <option value="Sewa Lapangan">Sewa Lapangan (Futsal)</option>
                                <option value="Sewa Alat Fitness">Sewa Alat Fitness (Insidental)</option>
                                <option value="Minuman/Snack">Kantin / Minuman</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-5 flex justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-4">
                        <button type="button" @click="showForm = false" class="bg-gray-100 dark:bg-gray-950 text-gray-500 dark:text-gray-400 px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition hover:bg-gray-200 dark:hover:bg-gray-900">Batal</button>
                        <button type="submit" class="bg-green-600 text-white px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-wider shadow-md hover:bg-green-700 transition">Simpan Transaksi</button>
                    </div>
                </form>
            </div>

            {{-- MAIN DATATABLE KAS MASUK --}}
            <div class="bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm dark:shadow-xl overflow-hidden backdrop-blur-sm">
                <div class="p-5 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-950/20">
                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider">Riwayat Arus Kas Masuk</h3>
                </div>
                
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-950/60 border-b border-gray-200 dark:border-gray-800 tracking-wider">
                            <tr>
                                <th class="px-6 py-4 w-12 text-center">No</th>
                                <th class="px-6 py-4">Waktu Transaksi</th>
                                <th class="px-6 py-4">Nama Pelanggan</th>
                                <th class="px-6 py-4 text-center">Tipe Anggota</th> 
                                <th class="px-6 py-4">Kategori Deskripsi</th>
                                <th class="px-6 py-4">Nominal</th>
                                <th class="px-6 py-4 text-center">Nota Bukti</th>
                                <th class="px-6 py-4 text-center">Status & Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800/60 font-medium">
                            @forelse($payments as $index => $payment)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-900/40 transition-colors duration-150">
                                <td class="px-6 py-4 text-center text-xs font-black text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-xs font-mono font-bold text-gray-400 dark:text-gray-500">
                                    {{ $payment->created_at->format('d/m/Y H:i') }} WIB
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white text-base">
                                    {{ $payment->user->name ?? $payment->external_customer_name }}
                                </td>
                                {{-- GANTI DUA KOLOM INI DI DALAM FORELSE TABEL KAMU --}}
                                {{-- 1. KOLOM TIPE ANGGOTA --}}
                               <td class="px-6 py-4 text-center">
                                    @if($payment->user_id)
                                        @php
                                            // Ambil tipe member (pelajar / umum) secara lowercase untuk validasi string
                                            $memberType = strtolower($payment->user->member?->type ?? 'umum');
                                        @endphp

                                        @if($memberType === 'pelajar')
                                            {{-- Badge Oranye Menyala untuk Kategori Pelajar/Mahasiswa --}}
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-200/60 dark:border-orange-500/20">
                                                Member Pelajar
                                            </span>
                                        @else
                                            {{-- Badge Biru Eksklusif untuk Kategori Umum --}}
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200/60 dark:border-blue-500/20">
                                                Member Umum
                                            </span>
                                        @endif
                                    @else
                                        {{-- Badge Abu-abu Netral untuk Pengunjung Luar / Non-Member --}}
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-gray-50 dark:bg-gray-900/40 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-800">
                                            GUEST VISIT
                                        </span>
                                    @endif
                                </td>

                                {{-- 2. KOLOM KATEGORI DESKRIPSI--}}
                                <td class="px-6 py-4 text-xs font-semibold text-gray-600 dark:text-gray-300">
                                    @if($payment->user_id)
                                        @php
                                            // Hitung kronologi transaksi sukses milik user ini
                                            $countPayments = \App\Models\Payment::where('user_id', $payment->user_id)
                                                ->whereIn('status', ['approved', 'verified'])
                                                ->where('created_at', '<=', $payment->created_at)
                                                ->count();
                                            
                                            // Ambil durasi bulan dari data transaksi, default ke 1 jika kosong
                                            $months = $payment->duration ?? 1;
                                        @endphp

                                        @if($countPayments <= 1)
                                            <span class="text-blue-600 dark:text-blue-400 font-bold flex flex-col gap-0.5">
                                                <span>Aktivasi Baru (Iuran Membership)</span>
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">Durasi Paket: {{ $months }} Bulan</span>
                                            </span>
                                        @else
                                            <span class="text-purple-600 dark:text-purple-400 font-bold flex flex-col gap-0.5">
                                                <span>Perpanjangan Member (Iuran Renewal)</span>
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">Durasi Paket: {{ $months }} Bulan</span>
                                            </span>
                                        @endif
                                    @else
                                        {{-- Jika dia Guest, tampilkan deskripsi manual dari form kasir (Sewa Lapangan, dll) --}}
                                        <span class="text-gray-700 dark:text-gray-300 font-medium block">
                                            {{ $payment->description ?? 'Layanan Insidental' }}
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 text-gray-900 dark:text-white font-black font-mono text-base">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>

                                {{-- BAGIAN PERBAIKAN STRIP PATH IMAGE --}}
                                <td class="px-6 py-4 text-center">
                                    @if($payment->proof_of_payment)
                                        {{-- Gunakan pembersih .replace() seperti modul database member agar kebal terhadap duplikasi path public --}}
                                        <a href="{{ asset(str_replace('storage/', '', $payment->proof_of_payment)) }}" target="_blank" class="inline-block group relative overflow-hidden rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                                            <img src="{{ asset(str_replace('storage/', '', $payment->proof_of_payment)) }}" 
                                                 class="w-10 h-10 object-cover transition duration-200 group-hover:scale-110" 
                                                 alt="Bukti">
                                        </a>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-600 italic text-[10px] font-mono tracking-wider bg-gray-50 dark:bg-gray-950 px-2 py-1 border border-gray-200 dark:border-gray-800 rounded-md">CASH / DIRECT</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col gap-1.5 items-center justify-center">
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-black tracking-widest uppercase shadow-sm {{ $payment->status == 'verified' || $payment->status == 'approved' ? 'bg-green-600 text-white' : 'bg-yellow-500 text-black animate-pulse' }}">
                                            {{ $payment->status }}
                                        </span>

                                        {{-- AKSI VALIDASI KASIR INSTAN --}}
                                        @if($payment->status == 'pending')
                                            <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST" class="w-full">
                                                @csrf
                                                @html('PATCH') {{-- Mengantisipasi pembacaan method verb di beberapa versi laravel --}}
                                                @method('PATCH')
                                                <button type="submit" class="w-full text-[9px] bg-blue-600 hover:bg-blue-700 text-white py-1 px-2.5 rounded-md font-black uppercase tracking-wider transition shadow-sm">
                                                    Validasi &rarr;
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center italic text-gray-400 dark:text-gray-600 font-bold">
                                    Belum terdeteksi adanya rekaman catatan transaksi keuangan masuk.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>