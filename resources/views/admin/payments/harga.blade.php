<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Master Harga Membership') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showEditModal: false, selectedPrice: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-200 font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Kategori & Harga</h3>
                    <p class="text-sm text-gray-500">Atur nominal iuran bulanan dan biaya pendaftaran untuk setiap kategori member.</p>
                </div>

                <div class="relative overflow-x-auto shadow-md sm:rounded-xl">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Harga Bulanan</th>
                                <th class="px-6 py-4">Biaya Pendaftaran</th>
                                <th class="px-6 py-4">Keterangan</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prices as $price)
                            <tr class="bg-white border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-900 uppercase tracking-tighter">
                                    {{ $price->category }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-blue-600">
                                    Rp {{ number_format($price->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 font-semibold text-orange-600">
                                    Rp {{ number_format($price->registration_fee, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-xs italic">
                                    {{ $price->description ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button 
                                        @click="selectedPrice = {{ json_encode($price) }}; showEditModal = true"
                                        class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 px-4 py-2 rounded-xl text-xs font-black uppercase transition">
                                        Edit Harga
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT HARGA --}}
        <div x-show="showEditModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-cloak>
            
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-black bg-opacity-50" @click="showEditModal = false"></div>

                <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 overflow-hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-black text-gray-900 uppercase">Edit Harga: <span class="text-indigo-600" x-text="selectedPrice.category"></span></h3>
                        <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                    </div>

                    <form :action="`{{ url('/admin/prices') }}/${selectedPrice.id}`" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block mb-1 text-xs font-black text-gray-500 uppercase tracking-widest">Harga Per Bulan (Rp)</label>
                            <input type="number" name="price" x-model="selectedPrice.price" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-3 focus:ring-indigo-500 focus:border-indigo-500 font-bold" required>
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-black text-gray-500 uppercase tracking-widest">Biaya Pendaftaran (Rp)</label>
                            <input type="number" name="registration_fee" x-model="selectedPrice.registration_fee" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-3 focus:ring-indigo-500 focus:border-indigo-500 font-bold" required>
                        </div>

                        <div>
                            <label class="block mb-1 text-xs font-black text-gray-500 uppercase tracking-widest">Keterangan Singkat</label>
                            <textarea name="description" x-model="selectedPrice.description" rows="2"
                                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-3 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="submit" class="flex-1 bg-indigo-600 text-white font-black py-4 rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition uppercase tracking-tighter">
                                Simpan Perubahan
                            </button>
                            <button type="button" @click="showEditModal = false" class="px-6 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition uppercase text-xs">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>