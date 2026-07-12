<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Konfigurasi Website</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-200 font-bold italic">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="bg-white shadow-sm sm:rounded-3xl p-8 border border-gray-100">
                    <h3 class="text-lg font-black text-gray-900 uppercase mb-6 border-b pb-2">Informasi Utama</h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block mb-2 text-xs font-black text-gray-500 uppercase">Nama Website / Usaha</label>
                            <input type="text" name="site_name" value="{{ old('site_name', $setting->site_name) }}" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 font-bold" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-black text-gray-500 uppercase">Deskripsi Singkat</label>
                            <textarea name="description" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3">{{ old('description', $setting->description) }}</textarea>
                        </div>
                    </div>

                    <h3 class="text-lg font-black text-gray-900 uppercase mt-10 mb-6 border-b pb-2">Kontak & Alamat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-xs font-black text-gray-500 uppercase">Nomor HP / WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3" placeholder="0812...">
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-black text-gray-500 uppercase">Email</label>
                            <input type="email" name="email" value="{{ old('email', $setting->email) }}" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3" placeholder="admin@piai.com">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-xs font-black text-gray-500 uppercase">Alamat Lengkap</label>
                            <textarea name="address" rows="2" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3">{{ old('address', $setting->address) }}</textarea>
                        </div>
                    </div>

                    {{-- --- BARU: KONFIGURASI METODE PEMBAYARAN DI APP MOBILE --- --}}
                    <h3 class="text-lg font-black text-gray-900 uppercase mt-10 mb-6 border-b pb-2">Metode Pembayaran (Aplikasi Mobile)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-xs font-black text-gray-500 uppercase">Nama Bank Transfer</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', $setting->bank_name) }}" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3 font-bold" placeholder="Contoh: BANK BCA, BANK MANDIRI">
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-black text-gray-500 uppercase">Nomor Rekening & Atas Nama</label>
                            <input type="text" name="bank_account" value="{{ old('bank_account', $setting->bank_account) }}" class="w-full bg-gray-50 border border-gray-300 rounded-xl p-3" placeholder="Contoh: 12345678 a/n Piai Fitness">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block mb-2 text-xs font-black text-gray-500 uppercase">Gambar / Kode QRIS</label>
                            @if($setting->qris_image)
                                <div class="mb-3">
                                    <p class="text-[10px] text-gray-400 mb-1 uppercase font-bold">QRIS Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $setting->qris_image) }}" class="h-44 rounded-xl border object-contain p-2 bg-gray-50">
                                </div>
                            @endif
                            <input type="file" name="qris_image" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2">
                            <p class="text-[10px] text-gray-400 mt-1">Format rekomendasi: Jpg/Png bentuk persegi untuk kerapian display di HP member.</p>
                        </div>
                    </div>
                    {{-- -------------------------------------------------------- --}}

                    <h3 class="text-lg font-black text-gray-900 uppercase mt-10 mb-6 border-b pb-2">Media & Visual</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block mb-2 text-xs font-black text-gray-500 uppercase">Logo Website</label>
                            @if($setting->logo)
                                <img src="{{ asset('storage/' . $setting->logo) }}" class="h-20 mb-3 rounded-lg border">
                            @endif
                            <input type="file" name="logo" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2">
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-black text-gray-500 uppercase">Gambar Banner (Hero)</label>
                            @if($setting->banner)
                                <img src="{{ asset('storage/' . $setting->banner) }}" class="h-20 mb-3 rounded-lg border object-cover w-full">
                            @endif
                            <input type="file" name="banner" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 p-2">
                        </div>
                    </div>

                    <div class="mt-10 pt-6">
                        <button type="submit" class="bg-indigo-600 text-white font-black px-10 py-4 rounded-2xl hover:bg-indigo-700 shadow-lg uppercase tracking-widest transition transform hover:-translate-y-1">Simpan Pengaturan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>