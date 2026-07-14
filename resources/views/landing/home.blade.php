<section id="home" class="relative min-h-screen flex items-center justify-center pt-20 px-10 bg-[#0f0d0d] overflow-hidden">
    {{-- Background Image dengan Overlay Gelap --}}
    <div class="absolute inset-0 z-0 opacity-40">
        <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070" alt="Gym" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-[#0f0d0d] via-[#0f0d0d]/80 to-transparent"></div>
    </div>

    <div class="relative z-10 w-full max-w-7xl flex flex-col lg:flex-row items-center gap-16">
        {{-- Teks Bagian Kiri --}}
        {{-- Teks Bagian Kiri --}}
<div class="flex-1 text-left z-10 flex flex-col justify-center">
    <div class="text-[#e8e6e1]/60 font-bold uppercase tracking-[0.3em] mb-4 text-xs">#1 GYM DI INDONESIA</div>
    
    {{-- Kita buat leading-tight agar tidak terlalu makan tempat --}}
    <h1 class="text-[60px] lg:text-[100px] font-black uppercase tracking-tighter leading-[0.9] text-[#e8e6e1] mb-8">
        TEMPA <br> <span class="text-red-600">DIRIMU</span> <br> JADILAH <br> LEGENDA
    </h1>
    
    <div class="w-20 h-1 bg-red-600 mb-8"></div>
    
    <p class="text-[#e8e6e1]/70 text-base lg:text-lg max-w-md mb-10 leading-relaxed">
        Bukan sekadar gym. {{ $siteSetting->site_name ?? 'PIAI FUTSAL FITNESS' }} adalah tempat di mana batas tubuhmu diuji, ambisimu ditempa, dan versi terbaik dirimu dilahirkan.
    </p>
    
    {{-- Tombol di sini sekarang punya wadah (div) yang aman --}}
    <div class="flex flex-wrap gap-4 mt-auto">
        <a href="{{ route('register') }}" class="px-8 py-4 bg-red-600 text-white font-black rounded-lg hover:bg-red-700 transition flex items-center gap-2 text-sm">
            GABUNG SEKARANG →
        </a>
        <button class="px-8 py-4 border border-[#444] text-[#e8e6e1] font-black rounded-lg hover:bg-white/10 transition text-sm">
            ▶ LIHAT VIDEO
        </button>
    </div>
</div>

        {{-- Image Card Bagian Kanan --}}
        <div class="relative w-full lg:w-[450px] aspect-square rounded-[2rem] overflow-hidden shadow-2xl border border-white/10">
            <img src="https://images.unsplash.com/photo-1594882645126-14020914d58d?q=80&w=1000" class="w-full h-full object-cover">
            
            {{-- Floating Card Member --}}
            <div class="absolute top-6 right-6 bg-red-600 text-white p-4 rounded-xl text-center">
                <div class="text-xl font-black">10.482</div>
                <div class="text-[9px] font-bold uppercase tracking-widest">MEMBER AKTIF</div>
            </div>

            {{-- Floating Card Ranking --}}
            <div class="absolute bottom-6 left-6 bg-[#1a1818]/90 backdrop-blur border border-white/10 p-4 rounded-xl flex items-center gap-3 text-white">
                <div class="bg-red-600/20 p-2 rounded-lg text-red-500 text-xl">🏆</div>
                <div>
                    <div class="font-black text-sm">#1 RANKED</div>
                    <div class="text-[9px] text-gray-400 font-bold uppercase">Fitness Center 2024</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer Stats --}}
    <div class="absolute bottom-10 left-10 flex gap-12 z-20">
        @foreach([['10K+', 'MEMBER'], ['98%', 'PUAS'], ['15+', 'TAHUN']] as $stat)
            <div>
                <div class="text-2xl font-black text-[#e8e6e1]">{{ $stat[0] }}</div>
                <div class="text-[10px] text-red-600 font-black uppercase tracking-widest">{{ $stat[1] }}</div>
            </div>
        @endforeach
    </div>
</section>