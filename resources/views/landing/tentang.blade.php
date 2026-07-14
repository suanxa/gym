<section id="tentang" class="py-24 px-10 bg-[#f5e6d3] text-gray-900">
    <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center">
        
        {{-- Sisi Kiri: Visual Collage --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="row-span-2 relative">
                <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=600" class="w-full h-full object-cover rounded-3xl shadow-xl">
                {{-- Badge Berdiri Sejak --}}
                <div class="absolute -bottom-6 -left-6 bg-red-600 text-white p-6 rounded-2xl shadow-xl rotate-[-5deg]">
                    <div class="text-4xl font-black">2009</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest">BERDIRI SEJAK</div>
                </div>
            </div>
            <div class="space-y-4">
                <img src="https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?q=80&w=400" class="w-full h-48 object-cover rounded-3xl shadow-lg">
                <img src="https://images.unsplash.com/photo-1517838350293-6052f5341399?q=80&w=400" class="w-full h-48 object-cover rounded-3xl shadow-lg">
            </div>
        </div>

        {{-- Sisi Kanan: Konten Informasi --}}
        <div>
            <div class="text-red-600 font-black uppercase tracking-[0.3em] text-xs mb-4">TENTANG KAMI</div>
            <h2 class="text-5xl lg:text-6xl font-black uppercase tracking-tighter leading-[0.9] text-gray-900 mb-8">
                LEBIH DARI <br> SEKADAR GYM — <br> <span class="text-red-600">INI KELUARGA</span>
            </h2>
            
            <p class="text-gray-700 leading-relaxed mb-8">
                {{ $siteSetting->site_name ?? 'PIAI FUTSAL FITNESS' }} didirikan dengan satu misi: menjadikan kebugaran prima sebagai hak semua orang, bukan privilege segelintir kalangan.
            </p>
            <p class="text-gray-700 leading-relaxed mb-10">
                Dari awal yang sederhana, kini kami telah berkembang dan berdedikasi melayani ribuan member dengan standar pelatihan internasional.
            </p>

            {{-- Grid Stats --}}
            <div class="grid grid-cols-2 gap-8 mb-10 border-t border-gray-300 pt-8">
                <div>
                    <div class="text-3xl font-black text-gray-900">8+</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-gray-500">Cabang Aktif</div>
                </div>
                <div>
                    <div class="text-3xl font-black text-gray-900">200+</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-gray-500">Trainer Bersertifikat</div>
                </div>
                <div>
                    <div class="text-3xl font-black text-gray-900">500+</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-gray-500">Kelas Per Bulan</div>
                </div>
                <div>
                    <div class="text-3xl font-black text-gray-900">24</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-gray-500">Award Diraih</div>
                </div>
            </div>

            <a href="{{ route('register') }}" class="inline-block px-10 py-5 bg-red-600 text-white font-black rounded-lg hover:bg-red-700 transition shadow-lg uppercase tracking-widest text-xs">
                AYO BERGABUNG →
            </a>
        </div>
    </div>
</section>