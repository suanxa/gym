<section id="kesehatan" class="py-24 px-10 bg-[#0f0d0d] border-t border-[#1a1818]">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Section --}}
        <div class="grid lg:grid-cols-2 gap-12 mb-20 items-end">
            <div>
                <div class="text-red-600 font-bold uppercase tracking-[0.3em] text-xs mb-4">KESEHATAN & WELLNESS</div>
                <h2 class="text-5xl lg:text-7xl font-black uppercase tracking-tighter leading-[0.9] text-[#e8e6e1]">
                    INVESTASI TERBAIK <br> <span class="text-red-600">ADALAH DIRIMU</span> <br> SENDIRI
                </h2>
            </div>
            <div class="space-y-6">
                <p class="text-[#e8e6e1]/70 leading-relaxed text-lg">
                    Kesehatan bukan kemewahan — ini kebutuhan. Kami percaya setiap orang berhak mendapatkan tubuh sehat dan jiwa kuat. Program holistik kami mencakup latihan fisik, nutrisi, dan kesehatan mental.
                </p>
                <a href="#" class="inline-block text-red-600 font-black uppercase tracking-widest text-xs hover:text-white transition">
                    LIHAT PROGRAM KAMI →
                </a>
            </div>
        </div>

        {{-- Statistik Bar --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 py-16 border-y border-[#1a1818] mb-20">
            @foreach([['10K+', 'MEMBER AKTIF'], ['98%', 'TINGKAT KEPUASAN'], ['15+', 'TAHUN PENGALAMAN'], ['50+', 'KELAS PER MINGGU']] as $stat)
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-black text-[#e8e6e1] mb-2">{{ $stat[0] }}</div>
                    <div class="text-[10px] text-[#e8e6e1]/50 font-bold uppercase tracking-[0.2em]">{{ $stat[1] }}</div>
                </div>
            @endforeach
        </div>

        {{-- Card Grid --}}
        <div class="grid md:grid-cols-3 gap-8">
            @foreach([
                ['STRENGTH & CARDIO', 'Latihan Fisik', 'Program latihan terstruktur yang disesuaikan dengan level dan tujuanmu.', 'https://images.unsplash.com/photo-1517838350293-6052f5341399?q=80&w=600'],
                ['DIET & NUTRITION', 'Nutrisi Optimal', 'Panduan gizi berbasis sains untuk memaksimalkan performa dan pemulihan.', 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?q=80&w=600'],
                ['MIND & BODY', 'Mental Wellness', 'Mindset juara dan manajemen stres untuk performa puncak.', 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?q=80&w=600']
            ] as $card)
                <div class="group bg-[#161414] rounded-3xl overflow-hidden border border-[#222] hover:border-red-900 transition-all">
                    <div class="h-64 overflow-hidden">
                        <img src="{{ $card[3] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <div class="p-8">
                        <span class="inline-block px-3 py-1 bg-red-600 text-white text-[9px] font-black tracking-widest uppercase mb-4 rounded">{{ $card[0] }}</span>
                        <h3 class="text-2xl font-black text-[#e8e6e1] mb-3">{{ $card[1] }}</h3>
                        <p class="text-[#e8e6e1]/60 text-sm leading-relaxed">{{ $card[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>