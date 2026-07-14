<section id="ulasan" class="py-24 px-10 bg-[#0f0d0d]">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Section --}}
        <div class="text-center mb-16">
            <div class="text-red-600 font-black uppercase tracking-[0.3em] text-xs mb-4">ULASAN MEMBER</div>
            <h2 class="text-5xl lg:text-7xl font-black uppercase tracking-tighter leading-[0.9] text-[#e8e6e1]">
                MEREKA SUDAH <br> <span class="text-red-600">MEMBUKTIKANNYA</span>
            </h2>
        </div>

        {{-- Grid Ulasan --}}
        <div class="grid md:grid-cols-3 gap-8 mb-24">
            @foreach([
                ['Budi Santoso', 'Software Engineer', 'Dalam 6 bulan bersama, berat badan saya turun 18kg. Trainernya luar biasa sabar dan program latihannya benar-benar terasa hasilnya!'],
                ['Sari Dewi', 'Dokter', 'Sebagai dokter, saya sangat menghargai pendekatan saintifik. Program nutrisinya berbasis riset dan hasilnya nyata.'],
                ['Andi Pratama', 'Atlet Lari', 'Fasilitas kelas dunia, trainer profesional, dan komunitas yang supportif. Ini bukan sekadar gym, ini rumah kedua saya.']
            ] as $ulasan)
                <div class="p-8 bg-[#161414] rounded-2xl border border-[#222] hover:border-red-900 transition-all">
                    <div class="text-red-600 mb-6 text-sm">★★★★★</div>
                    <p class="text-[#e8e6e1]/70 text-sm leading-relaxed mb-8 italic">"{{ $ulasan[2] }}"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center font-black text-xs text-white">
                            {{ substr($ulasan[0], 0, 1) }}
                        </div>
                        <div>
                            <div class="text-white font-black text-sm">{{ $ulasan[0] }}</div>
                            <div class="text-red-600 font-bold text-[10px] uppercase">{{ $ulasan[1] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- CTA BANNER PENUTUP --}}
        <div class="relative rounded-3xl overflow-hidden p-12 lg:p-20 bg-gradient-to-r from-red-900 to-red-600">
            <div class="relative z-10 grid lg:grid-cols-2 gap-10 items-center">
                <div>
                    <h3 class="text-4xl lg:text-5xl font-black uppercase tracking-tighter text-white mb-4">
                        SIAP MEMULAI <br> PERJALANANMU?
                    </h3>
                    <p class="text-red-100 font-bold tracking-widest text-xs uppercase">Trial gratis 7 hari · Tanpa komitmen · Tanpa risiko</p>
                </div>
                <div class="lg:text-right">
                    <a href="{{ route('register') }}" class="inline-block px-10 py-5 bg-white text-red-600 font-black rounded-lg hover:bg-gray-100 transition shadow-xl uppercase tracking-widest text-xs">
                        COBA GRATIS 7 HARI
                    </a>
                </div>
            </div>
            {{-- Background Overlay Pattern --}}
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
        </div>
    </div>
</section>