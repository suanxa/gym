<section id="fitur" class="py-24 px-10 bg-[#fef2f2]">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Fitur --}}
        <div class="text-center mb-16">
            <div class="text-red-600 font-black uppercase tracking-[0.3em] text-xs mb-4">FITUR EKSKLUSIF</div>
            <h2 class="text-5xl lg:text-6xl font-black uppercase tracking-tighter text-gray-900 mb-6">
                SEMUA YANG KAMU BUTUHKAN <br> <span class="text-red-600">ADA DI SINI</span>
            </h2>
            <p class="text-gray-600 max-w-xl mx-auto">Fasilitas lengkap, trainer profesional, dan komunitas yang mendukungmu — semua dalam satu atap.</p>
        </div>

        {{-- Grid Fitur --}}
        <div class="grid md:grid-cols-3 gap-6 mb-24">
            @foreach([
                ['Peralatan Premium', 'Lebih dari 200+ peralatan gym terkini dari brand kelas dunia.'],
                ['Pelatih Bersertifikat', 'Tim trainer profesional bersertifikat Internasional siap membimbingmu.'],
                ['Program Personal', 'Program latihan yang dirancang khusus sesuai kondisi tubuh & target.'],
                ['Nutrisi & Diet', 'Konsultasi gizi dan rencana diet personal yang selaras dengan program.'],
                ['Kompetisi Internal', 'Event dan kompetisi rutin bulanan untuk memacu semangatmu.'],
                ['Tracking Progress', 'Pantau kemajuanmu secara real-time melalui aplikasi eksklusif.']
            ] as $fitur)
                <div class="p-8 bg-[#f5e6d3] rounded-2xl border border-white shadow-sm hover:shadow-lg transition-all">
                    <div class="w-12 h-12 bg-white/50 rounded-lg flex items-center justify-center mb-6 text-red-600">★</div>
                    <h3 class="text-xl font-black text-gray-900 mb-3">{{ $fitur[0] }}</h3>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ $fitur[1] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Section Membership --}}
        <div class="text-center mb-16">
            <h2 class="text-4xl lg:text-5xl font-black uppercase tracking-tighter text-gray-900">
                PILIH PAKET <span class="text-red-600">MEMBERSHIP</span> <br> YANG COCOK UNTUKMU
            </h2>
        </div>

        {{-- Kartu Paket --}}
        <div class="grid lg:grid-cols-3 gap-8 items-center max-w-6xl mx-auto">
            {{-- Starter --}}
            <div class="p-8 bg-[#f5e6d3] rounded-3xl border border-gray-200">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Paket</div>
                <h3 class="text-3xl font-black mb-4">Starter</h3>
                <div class="text-4xl font-black mb-6">Rp 299k <span class="text-sm font-bold text-gray-500">/bulan</span></div>
                <ul class="space-y-4 mb-8 text-sm font-bold">
                    <li>✓ Akses gym 24/7</li><li>✓ Program latihan dasar</li><li>✓ Loker & shower</li>
                </ul>
                <button class="w-full py-4 bg-gray-900 text-white font-black rounded-lg hover:bg-red-600">PILIH PAKET</button>
            </div>

            {{-- Pro (Terpopuler) --}}
            <div class="relative p-10 bg-red-600 text-white rounded-3xl shadow-2xl scale-105 z-10">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-black text-white text-[10px] font-black uppercase rounded-full">Terpopuler</div>
                <div class="text-[10px] font-black uppercase tracking-widest text-red-200 mb-2">Paket</div>
                <h3 class="text-3xl font-black mb-4">Pro</h3>
                <div class="text-4xl font-black mb-6">Rp 599k <span class="text-sm font-bold text-red-200">/bulan</span></div>
                <ul class="space-y-4 mb-8 text-sm font-bold">
                    <li>✓ Semua fitur starter</li><li>✓ Personal Trainer</li><li>✓ Semua kelas grup</li><li>✓ Konsultasi nutrisi</li>
                </ul>
                <button class="w-full py-4 bg-white text-red-600 font-black rounded-lg">PILIH PAKET PRO</button>
            </div>

            {{-- Elite --}}
            <div class="p-8 bg-[#f5e6d3] rounded-3xl border border-gray-200">
                <div class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Paket</div>
                <h3 class="text-3xl font-black mb-4">Elite</h3>
                <div class="text-4xl font-black mb-6">Rp 999k <span class="text-sm font-bold text-gray-500">/bulan</span></div>
                <ul class="space-y-4 mb-8 text-sm font-bold">
                    <li>✓ Semua fitur pro</li><li>✓ Private trainer</li><li>✓ Guest pass 4x/bulan</li>
                </ul>
                <button class="w-full py-4 bg-gray-900 text-white font-black rounded-lg hover:bg-red-600">PILIH PAKET ELITE</button>
            </div>
        </div>
    </div>
</section>