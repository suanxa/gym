<x-app-layout>
    {{-- FORCE GLOBAL STYLE --}}
    <style>
        .dark body, .dark main, .dark .min-h-screen { background-color: #030712 !important; }
        body, main, .min-h-screen { background-color: #f3f4f6 !important; transition: background-color 0.2s ease; }
        .modal-open { overflow: hidden !important; }
    </style>

    <div class="p-4 sm:ml-1 bg-gray-100 dark:bg-gray-950 min-h-screen pt-2 text-gray-800 dark:text-gray-100 transition-colors duration-200" 
         x-data="{ showEditModal: false, showAddModal: false, selectedPrice: {} }"
         x-init="$watch('showEditModal', value => { if(value) document.body.classList.add('modal-open'); else document.body.classList.remove('modal-open'); }); $watch('showAddModal', value => { if(value) document.body.classList.add('modal-open'); else document.body.classList.remove('modal-open'); })">
         
        <div class="max-w-7xl mx-auto space-y-6">
            
            {{-- HEADER BANNER --}}
            <div class="p-6 bg-gradient-to-r from-white via-slate-50 to-red-50 dark:from-gray-950 dark:via-gray-900 dark:to-red-950/40 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-md flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black tracking-wide text-gray-900 dark:text-white uppercase">
                        Konfigurasi <span class="text-red-600 dark:text-red-500">Harga Paket</span>
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Atur nominal dan biaya untuk sinkronisasi ke aplikasi mobile.</p>
                </div>
                <button @click="showAddModal = true" class="bg-gray-900 dark:bg-white hover:bg-red-600 dark:hover:bg-red-600 text-white dark:text-gray-900 px-6 py-3 rounded-xl text-xs font-black uppercase tracking-wider shadow-lg transition">
                    + Tambah Paket
                </button>
            </div>

            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div class="p-4 text-sm text-green-800 dark:text-green-400 rounded-xl bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900/50 font-bold shadow-sm">
                    <span class="flex items-center gap-2"><i class="w-2 h-2 rounded-full bg-green-500"></i> {{ session('success') }}</span>
                </div>
            @endif

            {{-- TABEL --}}
            <div class="bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm overflow-hidden backdrop-blur-sm">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-950/60 border-b border-gray-200 dark:border-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-center">No</th> 
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Harga</th>
                                <th class="px-6 py-4">Pendaftaran</th>
                                <th class="px-6 py-4">Keterangan</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800/60 font-medium">
                            @foreach($prices as $index => $price)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-900/40">
                                <td class="px-6 py-4 text-center text-xs font-black text-gray-400">{{ $index + 1 }}</td> 
                                <td class="px-6 py-4 font-bold">{{ $price->category }}</td>
                                <td class="px-6 py-4 font-black font-mono text-base text-gray-900 dark:text-white">Rp {{ number_format($price->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 font-black font-mono text-base text-red-600 dark:text-red-400">Rp {{ number_format($price->registration_fee, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-xs italic text-gray-600 dark:text-gray-400 truncate max-w-[150px]">{{ $price->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button @click="selectedPrice = {{ json_encode($price) }}; showEditModal = true" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase">Edit</button>
                                        <form action="{{ route('admin.prices.destroy', $price->id) }}" method="POST" onsubmit="return confirm('Hapus paket ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="bg-gray-100 dark:bg-gray-800 hover:bg-red-50 text-gray-500 hover:text-red-600 px-4 py-2 rounded-xl text-[10px] font-black uppercase">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MODAL TAMBAH --}}
        <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm" @click="showAddModal = false"></div>
            <div class="relative bg-white dark:bg-gray-950 rounded-3xl shadow-2xl w-full max-w-md p-6">
                <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-5">Tambah Paket Baru</h3>
                <form action="{{ route('admin.prices.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-1">Nama Kategori</label>
                        <input type="text" name="category" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 rounded-xl p-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-1">Harga Bulanan</label>
                        <input type="number" name="price" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 rounded-xl p-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-1">Biaya Pendaftaran</label>
                        <input type="number" name="registration_fee" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 rounded-xl p-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-1">Keterangan</label>
                        <textarea name="description" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 rounded-xl p-3 text-sm" rows="2"></textarea>
                    </div>
                    <div class="pt-4 flex gap-2">
                        <button type="submit" class="flex-1 bg-red-600 text-white font-black py-3 rounded-xl">SIMPAN</button>
                        <button type="button" @click="showAddModal = false" class="px-6 bg-gray-100 dark:bg-gray-900 text-gray-500 font-bold rounded-xl">BATAL</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm" @click="showEditModal = false"></div>
            <div class="relative bg-white dark:bg-gray-950 rounded-3xl shadow-2xl w-full max-w-md p-6">
                <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase mb-5">Update Tarif</h3>
                <form :action="`{{ url('/admin/prices') }}/${selectedPrice.id}`" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-1">Nama Kategori</label>
                        <input type="text" name="category" x-model="selectedPrice.category" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 rounded-xl p-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-1">Harga Bulanan</label>
                        <input type="number" name="price" x-model="selectedPrice.price" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 rounded-xl p-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-1">Biaya Pendaftaran</label>
                        <input type="number" name="registration_fee" x-model="selectedPrice.registration_fee" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 rounded-xl p-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-500 mb-1">Keterangan</label>
                        <textarea name="description" x-model="selectedPrice.description" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 rounded-xl p-3 text-sm" rows="2"></textarea>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="submit" class="flex-1 bg-red-600 text-white font-black py-3 rounded-xl">SIMPAN</button>
                        <button type="button" @click="showEditModal = false" class="px-5 bg-gray-100 dark:bg-gray-900 text-gray-500 font-bold rounded-xl">BATAL</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>