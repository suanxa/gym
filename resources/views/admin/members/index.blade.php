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
        /* Mengunci scroll body utama saat modal terbuka agar tidak melompat */
        .modal-open {
            overflow: hidden !important;
        }
    </style>

    <div class="p-4 sm:ml-1 bg-gray-100 dark:bg-gray-950 min-h-screen pt-2 text-gray-800 dark:text-gray-100 transition-colors duration-200" 
            x-data="{ 
                members: {{ $members->toJson() }}, 
                search: '', 
                page: 1, 
                perPage: 10,
                selectedMember: null, 
                showModal: false,
                get filteredMembers() {
                    return this.members.filter(m => m.user.name.toLowerCase().includes(this.search.toLowerCase()));
                },
                get paginatedMembers() {
                    let start = (this.page - 1) * this.perPage;
                    return this.filteredMembers.slice(start, start + this.perPage);
                },
                get totalPages() {
                    return Math.ceil(this.filteredMembers.length / this.perPage);
                }
            }">
         
        <div class="max-w-7xl mx-auto space-y-6">
            
            {{-- HEADER BANNER MODUL --}}
            <div class="p-6 bg-gradient-to-r from-white via-slate-50 to-red-50 dark:from-gray-950 dark:via-gray-900 dark:to-red-950/40 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black tracking-wide text-gray-900 dark:text-white uppercase">
                        Database <span class="text-red-600 dark:text-red-500">Anggota</span>
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Verifikasi, manajemen masa aktif, dan validasi berkas fisik member Piai Wellness.</p>
                </div>
                <div class="px-4 py-2 bg-red-600/10 dark:bg-red-600/20 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900/50 rounded-xl text-xs font-bold tracking-wider uppercase">
                    Total: {{ count($members) }} Records
                </div>
            </div>

            {{-- NOTIFIKASI SYSTEM --}}
            @if(session('success'))
                <div class="p-4 text-sm text-green-800 dark:text-green-400 rounded-xl bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900/50 font-bold shadow-sm">
                    <span class="flex items-center gap-2"><i class="w-2 h-2 rounded-full bg-green-500"></i> {{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 text-sm text-red-800 dark:text-red-400 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 font-bold shadow-sm">
                    <span class="flex items-center gap-2"><i class="w-2 h-2 rounded-full bg-red-500"></i> {{ session('error') }}</span>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
                <input type="text" x-model="search" placeholder="Cari nama member..." 
                    class="w-full sm:w-64 px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl focus:ring-2 focus:ring-red-500 outline-none transition">
            </div>

            {{-- MAIN DATATABLE CONTAINER --}}
            <div class="bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm dark:shadow-xl overflow-hidden backdrop-blur-sm">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-950/60 border-b border-gray-200 dark:border-gray-800 tracking-wider">
                            <tr>
                                <th class="px-6 py-4 w-12 text-center">No</th> <!-- Kolom Nomor -->
                                <th class="px-6 py-4">Nama Member</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Status Akun</th>
                                <th class="px-6 py-4">Masa Berlaku</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800/60 font-medium">
    {{-- Menggunakan template Alpine untuk melakukan looping pada data yang sudah difilter dan dipaginate --}}
    <template x-for="(member, index) in paginatedMembers" :key="member.id">
        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-900/40 transition-colors duration-150">
            {{-- Nomor Urut Dinamis --}}
            <td class="px-6 py-4 text-center text-xs font-black text-gray-400 dark:text-gray-600" 
                x-text="(page - 1) * perPage + index + 1">
            </td>
            
            {{-- Nama Member --}}
            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white text-base" 
                x-text="member.user.name">
            </td>

            {{-- Kategori --}}
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black tracking-wide uppercase"
                      :class="member.type == 'pelajar' 
                        ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-500/20' 
                        : 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20'">
                    <span x-text="member.type"></span>
                </span>
            </td>

            {{-- Status --}}
            <td class="px-6 py-4">
                <div class="flex flex-col gap-1">
                    <span class="text-sm font-black tracking-wider uppercase" 
                          :class="member.status == 'active' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                          x-text="member.status">
                    </span>
                    {{-- Cek apakah ada pembayaran pending --}}
                    <template x-if="member.payments && member.payments.length > 0 && member.payments[0].status == 'pending'">
                        <span class="text-[9px] bg-yellow-500 text-black px-2 py-0.5 rounded-md font-black animate-pulse w-fit tracking-wide">
                            <span x-text="member.status == 'active' ? 'ANTREAN RE-NEWAL' : 'ANTREAN AKTIVASI'"></span>
                        </span>
                    </template>
                </div>
            </td>

            {{-- Masa Berlaku --}}
            <td class="px-6 py-4 text-xs font-mono font-bold text-gray-600 dark:text-gray-300">
                <template x-if="member.membership_expiry">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                        <span x-text="member.membership_expiry"></span>
                    </span>
                </template>
                <template x-if="!member.membership_expiry">
                    <span class="text-gray-400 dark:text-gray-600 italic">Belum Diaktivasi</span>
                </template>
            </td>

            {{-- Aksi --}}
            <td class="px-6 py-4 text-center">
                <button @click="selectedMember = member; showModal = true" 
                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-xl text-xs font-black tracking-wide transition duration-150 shadow-md uppercase">
                    Periksa Berkas &rarr;
                </button>
            </td>
        </tr>
    </template>

    {{-- Kondisi jika hasil pencarian kosong --}}
    <template x-if="filteredMembers.length === 0">
        <tr>
            <td colspan="6" class="px-6 py-12 text-center italic text-gray-400 dark:text-gray-600 font-bold">
                Tidak ditemukan rekaman data anggota yang sesuai.
            </td>
        </tr>
    </template>
</tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex justify-between items-center">
                    <button @click="page--" :disabled="page == 1" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg text-xs font-bold disabled:opacity-50">Sebelumnya</button>
                    <span class="text-xs font-bold text-gray-500">Halaman <span x-text="page"></span> dari <span x-text="totalPages || 1"></span></span>
                    <button @click="page++" :disabled="page >= totalPages" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-lg text-xs font-bold disabled:opacity-50">Selanjutnya</button>
                </div>
            </div>
        </div>

        {{-- [FIXED LAYOUT]: MODAL SEKARANG FIXED OVERLAY DI ATAS LAYLAYER UTAMA, TANPA MELOMPAT --}}
        <div x-show="showModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-cloak>
            
            {{-- Backdrop Kaca Gelap Terkunci --}}
            <div class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm" @click="showModal = false"></div>

            {{-- Konten Box Modal Tengah --}}
            <div class="relative bg-white dark:bg-gray-950 rounded-3xl text-left shadow-2xl border border-gray-200 dark:border-gray-800 w-full sm:max-w-2xl max-h-[90vh] flex flex-col z-10"
                 @click.away="showModal = false">
                
                {{-- Modal Header --}}
                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-900 p-5 flex-shrink-0">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wide flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-red-600 rounded-full animate-pulse"></span> Verifikasi Berkas Fisik
                    </h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl font-bold transition focus:outline-none">&times;</button>
                </div>

                {{-- Modal Body (Scrollable internal jika data panjang) --}}
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                    <template x-if="selectedMember">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Sisi Kiri: Biodata Anggota --}}
                            <div class="space-y-4">
                                <div class="p-3.5 bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-800 rounded-xl">
                                    <p class="text-[9px] text-gray-400 dark:text-gray-500 uppercase font-black tracking-widest">Nama Lengkap Anggota</p>
                                    <p class="font-black text-gray-900 dark:text-white text-lg uppercase mt-0.5" x-text="selectedMember.user.name"></p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="p-3 bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-800 rounded-xl">
                                        <p class="text-[9px] text-gray-400 dark:text-gray-500 uppercase font-black tracking-widest">WhatsApp</p>
                                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200 mt-0.5" x-text="selectedMember.phone_number || '-'"></p>
                                    </div>
                                    <div class="p-3 bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-800 rounded-xl">
                                        <p class="text-[9px] text-gray-400 dark:text-gray-500 uppercase font-black tracking-widest">Gender</p>
                                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200 mt-0.5" x-text="selectedMember.gender == 'L' ? 'Laki-laki' : 'Perempuan'"></p>
                                    </div>
                                </div>

                                <div class="p-3 bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-800 rounded-xl">
                                    <p class="text-[9px] text-gray-400 dark:text-gray-500 uppercase font-black tracking-widest">Alamat Rumah</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 font-semibold leading-relaxed mt-0.5" x-text="selectedMember.address || '-'"></p>
                                </div>

                                <template x-if="selectedMember.membership_expiry">
                                    <div class="p-3 bg-red-600/5 border border-red-500/10 rounded-xl">
                                        <p class="text-[9px] text-red-500 dark:text-red-400 uppercase font-black tracking-widest">Masa Kadaluarsa Saat Ini</p>
                                        <p class="text-sm font-mono font-bold text-red-600 dark:text-red-400 mt-0.5" x-text="selectedMember.membership_expiry"></p>
                                    </div>
                                </template>
                            </div>

                            {{-- SISI KANAN: BUKTI GAMBAR BERKAS FISIK (OPTIMALISASI DUAL PATH PUBLIC MURNI) --}}
                            <div class="space-y-4">
                                {{-- 1. VALIDASI KARTU PELAJAR --}}
                                <div x-show="selectedMember.type == 'pelajar'">
                                    <p class="text-[10px] text-orange-600 dark:text-orange-400 uppercase font-black tracking-wider mb-1 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Validasi Kartu Pelajar Aktif
                                    </p>
                                    <template x-if="selectedMember.student_card">
                                        {{-- Cukup bersihkan string 'storage/' jika ada, lalu tembak langsung ke root public asset --}}
                                        <a :href="`{{ asset('') }}${selectedMember.student_card.replace('storage/', '')}`" target="_blank" class="block group relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800">
                                            <img :src="`{{ asset('') }}${selectedMember.student_card.replace('storage/', '')}`" 
                                                class="w-full h-24 object-cover transition duration-300 group-hover:scale-105">
                                        </a>
                                    </template>
                                </div>
                                
                                {{-- 2. BUKTI TRANSFER BANK / QRIS --}}
                                <div>
                                    <p class="text-[10px] text-blue-600 dark:text-blue-400 uppercase font-black tracking-wider mb-1 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Bukti Transfer Bank / QRIS
                                    </p>
                                    <template x-if="selectedMember.payments && selectedMember.payments.length > 0">
                                        {{-- Cukup bersihkan string 'storage/' jika ada, lalu tembak langsung ke root public asset --}}
                                        <a :href="`{{ asset('') }}${selectedMember.payments[0].proof_of_payment.replace('storage/', '')}`" target="_blank" class="block group relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800">
                                            <img :src="`{{ asset('') }}${selectedMember.payments[0].proof_of_payment.replace('storage/', '')}`"
                                                class="w-full h-36 object-cover transition duration-300 group-hover:scale-105">
                                        </a>
                                    </template>
                                    <template x-if="!selectedMember.payments || selectedMember.payments.length == 0">
                                        <div class="h-36 bg-gray-50 dark:bg-gray-900/60 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-xl flex items-center justify-center">
                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-bold text-center leading-relaxed">Belum ada unggahan berkas</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Modal Action Footer --}}
                <div class="p-5 border-t border-gray-100 dark:border-gray-900 flex flex-col sm:flex-row justify-between items-center gap-3 flex-shrink-0">
                    <template x-if="selectedMember && selectedMember.payments.length > 0 && selectedMember.payments[0].status == 'pending'">
                        <div class="flex items-center gap-2 w-full sm:w-auto flex-1">
                            <form :action="`{{ url('/admin/members/approve') }}/${selectedMember.id}`" method="POST" class="flex-1 sm:flex-initial">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-xs font-black px-4 py-3 rounded-xl shadow-md uppercase tracking-wide">
                                    Konfirmasi Bayar (<span x-text="selectedMember.payments[0].duration"></span> Bln)
                                </button>
                            </form>

                            <form :action="`{{ url('/admin/members/reject') }}/${selectedMember.id}`" method="POST" class="flex-1 sm:flex-initial" onsubmit="return confirm('Apakah Anda yakin ingin menolak berkas pembayaran ini?')">
                                @csrf
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-black px-4 py-3 rounded-xl shadow-md uppercase tracking-wide">
                                    Tolak Berkas
                                </button>
                            </form>
                        </div>
                    </template>

                    <template x-if="selectedMember && (selectedMember.payments.length == 0 || selectedMember.payments[0].status != 'pending')">
                        <div class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/60 px-4 py-2.5 border rounded-xl w-full sm:w-auto text-center">
                            Status Kas: <span class="text-red-500 tracking-widest font-black" x-text="selectedMember.payments.length > 0 ? selectedMember.payments[0].status.toUpperCase() : 'NO RECORDS'"></span>
                        </div>
                    </template>
                    
                    <button @click="showModal = false" class="w-full sm:w-auto px-5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 font-bold py-3 rounded-xl transition text-xs uppercase">
                        Tutup Jendela
                    </button>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>