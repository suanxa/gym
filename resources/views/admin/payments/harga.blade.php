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

    {{-- [FIXED MARGIN & RESPONSIVE DESIGN] --}}
    <div class="p-4 sm:ml-1 bg-gray-100 dark:bg-gray-950 min-h-screen pt-2 text-gray-800 dark:text-gray-100 transition-colors duration-200" 
         x-data="{ showEditModal: false, selectedPrice: {} }"
         x-init="$watch('showEditModal', value => {
            if (value) document.body.classList.add('modal-open');
            else document.body.classList.remove('modal-open');
         })">
         
        <div class="max-w-7xl mx-auto space-y-6">
            
            {{-- HEADER BANNER MODUL --}}
            <div class="p-6 bg-gradient-to-r from-white via-slate-50 to-red-50 dark:from-gray-950 dark:via-gray-900 dark:to-red-950/40 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black tracking-wide text-gray-900 dark:text-white uppercase">
                        Konfigurasi <span class="text-red-600 dark:text-red-500">Harga Paket</span>
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Atur nominal iuran bulanan dan biaya pendaftaran awal untuk sinkronisasi otomatis ke aplikasi mobile.</p>
                </div>
            </div>

            {{-- NOTIFIKASI SYSTEM --}}
            @if(session('success'))
                <div class="p-4 text-sm text-green-800 dark:text-green-400 rounded-xl bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900/50 font-bold shadow-sm">
                    <span class="flex items-center gap-2"><i class="w-2 h-2 rounded-full bg-green-500"></i> {{ session('success') }}</span>
                </div>
            @endif

            {{-- MAIN DATATABLE CONTAINER --}}
            <div class="bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm dark:shadow-xl overflow-hidden backdrop-blur-sm">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 dark:text-gray-400 uppercase bg-gray-50 dark:bg-gray-950/60 border-b border-gray-200 dark:border-gray-800 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Kategori Paket</th>
                                <th class="px-6 py-4">Harga Bulanan</th>
                                <th class="px-6 py-4">Biaya Pendaftaran</th>
                                <th class="px-6 py-4">Keterangan Aturan</th>
                                <th class="px-6 py-4 text-center">Aksi Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800/60 font-medium">
                            @foreach($prices as $price)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-900/40 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ strtolower($price->category) == 'pelajar' ? 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-200/60 dark:border-orange-500/20' : 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-200/60 dark:border-blue-500/20' }}">
                                        {{ $price->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-black font-mono text-base text-gray-900 dark:text-white">
                                    Rp {{ number_format($price->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 font-black font-mono text-base text-red-600 dark:text-red-400">
                                    Rp {{ number_format($price->registration_fee, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-xs italic text-gray-600 dark:text-gray-400 max-w-xs truncate">
                                    {{ $price->description ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button 
                                        @click="selectedPrice = {{ json_encode($price) }}; showEditModal = true"
                                        class="inline-flex items-center text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-xl text-xs font-black tracking-wide transition duration-150 shadow-md uppercase">
                                        Edit Nominal
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- PREMIUM OVERLAY MODAL EDIT HARGA (FIXED DI TENGAH LAYAR) --}}
        <div x-show="showEditModal" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-cloak>
            
            {{-- Backdrop Buram Terkunci --}}
            <div class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm" @click="showEditModal = false"></div>

            {{-- Konten Box Modal Tengah --}}
            <div class="relative bg-white dark:bg-gray-950 rounded-3xl text-left shadow-2xl border border-gray-200 dark:border-gray-800 w-full max-w-md p-6 z-10 animate-fade-in"
                 @click.away="showEditModal = false">
                
                {{-- Modal Header --}}
                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-900 pb-4 mb-5">
                    <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-wide flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 bg-red-600 rounded-full animate-pulse"></span> Update Tarif: <span class="text-red-600 dark:text-red-500" x-text="selectedPrice.category"></span>
                    </h3>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl font-bold transition focus:outline-none">&times;</button>
                </div>

                {{-- Form Input --}}
                <form :action="`{{ url('/admin/prices') }}/${selectedPrice.id}`" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-2 text-xs font-black uppercase text-gray-500 dark:text-gray-400 tracking-wider">Harga Per Bulan (Rp)</label>
                        <input type="number" name="price" x-model="selectedPrice.price" 
                               class="bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 text-gray-900 dark:text-white text-sm rounded-xl block w-full p-3 focus:ring-2 focus:ring-red-500 focus:border-transparent focus:outline-none transition font-mono font-bold" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-black uppercase text-gray-500 dark:text-gray-400 tracking-wider">Biaya Pendaftaran Awal (Rp)</label>
                        <input type="number" name="registration_fee" x-model="selectedPrice.registration_fee" 
                               class="bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 text-gray-900 dark:text-white text-sm rounded-xl block w-full p-3 focus:ring-2 focus:ring-red-500 focus:border-transparent focus:outline-none transition font-mono font-bold" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-black uppercase text-gray-500 dark:text-gray-400 tracking-wider">Keterangan Singkat Aturan</label>
                        <textarea name="description" x-model="selectedPrice.description" rows="2"
                                  class="bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 text-gray-900 dark:text-white text-sm rounded-xl block w-full p-3 focus:ring-2 focus:ring-red-500 focus:border-transparent focus:outline-none transition font-semibold" placeholder="Tambahkan deskripsi hak akses paket..."></textarea>
                    </div>

                    {{-- Modal Actions Footer --}}
                    <div class="pt-4 flex gap-3 border-t border-gray-100 dark:border-gray-900 mt-5">
                        <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-black py-3 rounded-xl shadow-md transition transform hover:-translate-y-0.5 uppercase text-xs tracking-wider">
                            Simpan Perubahan
                        </button>
                        <button type="button" @click="showEditModal = false" class="px-5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-900 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 font-bold rounded-xl transition text-xs uppercase">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>