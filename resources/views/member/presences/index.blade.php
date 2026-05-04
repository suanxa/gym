<x-app-layout>
    <style>
        @media print {
            body * { visibility: hidden; }
            #membership-card, #membership-card * { visibility: visible; }
            #membership-card {
                position: fixed;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                width: 450px;
                box-shadow: none;
                border: none;
            }
            .no-print { display: none !important; }
        }
    </style>
        {{-- Gunakan -ml-4 jika perlu untuk membuang padding p-4 dari parent --}}
        <div class="mt-14 w-full flex flex-col items-center justify-center">
            
            @if(auth()->user()->member?->status === 'active')
                
                {{-- WRAPPER UTAMA KARTU --}}
                <div class="w-full max-w-5xl flex flex-col items-center"> {{-- max-w-5xl memastikan dia punya ruang luas untuk center --}}
                    
                    <div class="py-10">
                        <div id="membership-card" class="relative w-[450px] overflow-hidden rounded-[2.5rem] shadow-2xl bg-slate-900">
                            {{-- Ornamen --}}
                            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-blue-600 rounded-full opacity-20 blur-3xl"></div>
                            
                            <div class="relative p-10 text-white text-left">
                                {{-- Header --}}
                                <div class="flex justify-between items-center mb-12">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-white/10 rounded-xl border border-white/20">
                                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        </div>
                                        <div>
                                            <h1 class="text-xl font-black italic leading-none">PIAI GYM</h1>
                                            <p class="text-[8px] tracking-[0.3em] text-blue-300 font-bold uppercase">Fitness Center</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-blue-500 text-[10px] font-black rounded-full uppercase tracking-tighter shadow-lg shadow-blue-500/20">Official Member</span>
                                </div>

                                {{-- Info Member --}}
                                <div class="space-y-5">
                                    <div>
                                        <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest mb-1">Nama Lengkap</p>
                                        <p class="text-2xl font-black tracking-tight">{{ strtoupper(auth()->user()->name) }}</p>
                                    </div>
                                    <div class="flex gap-10">
                                        <div>
                                            <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest mb-1">Member ID</p>
                                            <p class="font-mono font-bold">PG-{{ str_pad(auth()->user()->member->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest mb-1">Kategori</p>
                                            <p class="font-bold uppercase text-indigo-200">{{ auth()->user()->member->type }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-blue-400 font-bold uppercase tracking-widest mb-1">Alamat</p>
                                        <p class="text-xs text-gray-400 italic line-clamp-1">{{ auth()->user()->member->address }}</p>
                                    </div>
                                </div>

                                {{-- Footer --}}
                                <div class="mt-12 flex justify-between items-end pt-6 border-t border-white/10">
                                    <div>
                                        <p class="text-[9px] text-blue-400 font-bold uppercase mb-1">Berlaku Hingga</p>
                                        <p class="text-sm font-bold">{{ \Carbon\Carbon::parse(auth()->user()->member->membership_expiry)->format('d M Y') }}</p>
                                    </div>
                                    <div class="bg-white p-1 rounded-lg">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=MEMBER-{{ auth()->user()->id }}" class="w-12 h-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Cetak --}}
                    <div class="no-print mb-10">
                        <button onclick="window.print()" class="px-12 py-4 bg-blue-600 text-white font-black rounded-2xl shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all flex items-center gap-3 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            CETAK KARTU MEMBERSHIP
                        </button>
                    </div>

                </div>

            @endif

           {{-- Riwayat Kehadiran (No Print) --}}
            <div class="no-print w-full max-w-4xl mt-10 px-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                    <h2 class="text-2xl font-extrabold text-gray-800 flex items-center gap-3">
                        <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                        Riwayat Kehadiran
                    </h2>
                    
                    {{-- Tombol Presensi: Hanya muncul jika status ACTIVE --}}
@if(auth()->user()->member?->status === 'active')
    @php
        // Cek apakah sudah ada absen hari ini untuk user ini
        $hasCheckedInToday = auth()->user()->presences()
                            ->whereDate('check_in', \Carbon\Carbon::today())
                            ->exists();
    @endphp

    <form action="{{ route('member.presences.store') }}" method="POST">
        @csrf
        @if(!$hasCheckedInToday)
            {{-- Tombol AKTIF jika belum absen --}}
            <button type="submit" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-100 transition-all active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Absen Masuk Sekarang
            </button>
        @else
            {{-- Tombol NON-AKTIF jika sudah absen --}}
            <div class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-500 font-bold rounded-xl cursor-not-allowed border border-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Sudah Absen Hari Ini
            </div>
        @endif
    </form>
@endif
                </div>

                {{-- Tabel Riwayat --}}
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">No</th>
                                <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Waktu Masuk</th>
                                <th class="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($presences as $index => $presence)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($presence->check_in)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-blue-600 font-bold">
                                    {{ \Carbon\Carbon::parse($presence->check_in)->format('H:i') }} WIB
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-black rounded-full uppercase">Hadir</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                                    Belum ada catatan kehadiran bulan ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</x-app-layout>