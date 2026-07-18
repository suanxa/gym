<x-app-layout>
    <style>
        .dark body, .dark main, .dark .min-h-screen { background-color: #030712 !important; }
        body, main, .min-h-screen { background-color: #f3f4f6 !important; transition: background-color 0.2s ease; }
    </style>

    <div class="p-4 sm:ml-1 min-h-screen pt-2 text-gray-800 dark:text-gray-100 transition-colors duration-200">
        <div class="max-w-5xl mx-auto">
            
            {{-- HEADER BANNER --}}
            <div class="mb-8">
                <h1 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter">Konfigurasi Sistem</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Atur profil bisnis, kontak, dan aset visual aplikasi PIAI WELLNESS.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 text-sm text-green-800 dark:text-green-400 rounded-2xl bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900/50 font-bold italic">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
    <div class="mb-6 p-4 text-sm text-red-800 dark:text-red-400 rounded-2xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 font-bold">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- CARD 1: INFORMASI UTAMA & KONTAK --}}
                    <div class="md:col-span-2 bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 p-8 rounded-3xl shadow-sm">
                        <h3 class="text-xs font-black uppercase text-indigo-600 mb-6 tracking-widest">Informasi Dasar & Kontak</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase">Nama Website / Usaha</label>
                                <input type="text" name="site_name" value="{{ old('site_name', $setting->site_name) }}" class="w-full bg-gray-50 dark:bg-gray-950 border-0 border-b-2 border-gray-200 dark:border-gray-800 p-3 font-black text-lg focus:ring-0 focus:border-indigo-600 dark:text-white" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase">Nomor WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}" class="w-full bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl p-3 font-mono dark:text-white focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase">Email Bisnis</label>
                                <input type="email" name="email" value="{{ old('email', $setting->email) }}" class="w-full bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl p-3 dark:text-white focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase">Alamat Lengkap</label>
                                <textarea name="address" rows="2" class="w-full bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl p-3 dark:text-white focus:ring-2 focus:ring-indigo-500">{{ old('address', $setting->address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: PEMBAYARAN MOBILE --}}
                    <div class="bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 p-8 rounded-3xl shadow-sm">
                        <h3 class="text-xs font-black uppercase text-indigo-600 mb-6 tracking-widest">Metode Pembayaran (Mobile)</h3>
                        <div class="space-y-4">
                            <input type="text" name="bank_name" value="{{ old('bank_name', $setting->bank_name) }}" class="w-full bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl p-3 font-bold dark:text-white" placeholder="Nama Bank">
                            <input type="text" name="bank_account" value="{{ old('bank_account', $setting->bank_account) }}" class="w-full bg-gray-50 dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl p-3 font-mono dark:text-white" placeholder="No Rekening">
                            <div class="pt-2">
                                <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase">QRIS Image</label>
                                @if($setting->qris_image)
                                    <img src="{{ asset('storage/' . $setting->qris_image) }}" class="h-24 w-24 mb-3 rounded-xl border border-gray-200 dark:border-gray-800 object-cover">
                                @endif
                                <input type="file" name="qris_image" class="w-full text-xs text-gray-500 border border-gray-200 dark:border-gray-800 rounded-xl bg-gray-50 dark:bg-gray-950 p-2">
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: MEDIA VISUAL --}}
                    <div class="bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 p-8 rounded-3xl shadow-sm">
                        <h3 class="text-xs font-black uppercase text-indigo-600 mb-6 tracking-widest">Media & Visual</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase">Logo Website</label>
                                @if($setting->logo)
                                    <img src="{{ asset('storage/' . $setting->logo) }}" class="h-16 mb-2 rounded-xl border border-gray-200 dark:border-gray-800 p-1 bg-white">
                                @endif
                                <input type="file" name="logo" class="w-full text-xs text-gray-500 border border-gray-200 dark:border-gray-800 rounded-xl bg-gray-50 dark:bg-gray-950 p-2">
                            </div>
                            <div>
                                <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase">Banner Hero</label>
                                @if($setting->banner)
                                    <img src="{{ asset('storage/' . $setting->banner) }}" class="h-16 w-full mb-2 rounded-xl border border-gray-200 dark:border-gray-800 object-cover">
                                @endif
                                <input type="file" name="banner" class="w-full text-xs text-gray-500 border border-gray-200 dark:border-gray-800 rounded-xl bg-gray-50 dark:bg-gray-950 p-2">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="flex justify-end pb-12">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-12 py-4 rounded-2xl shadow-xl shadow-indigo-600/20 uppercase tracking-widest transition transform hover:-translate-y-1">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>