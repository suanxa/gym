<section id="fitur" class="py-24 px-10 bg-[#fef2f2]">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header Fitur --}}
        <div class="text-center mb-16">
            <div class="text-red-600 font-black uppercase tracking-[0.3em] text-xs mb-4">FITUR EKSKLUSIF</div>
            <h2 class="text-5xl lg:text-6xl font-black uppercase tracking-tighter text-gray-900 mb-6">
                SEMUA YANG KAMU BUTUHKAN <br> <span class="text-red-600">ADA DI SINI</span>
            </h2>
            <p class="text-gray-600 max-w-xl mx-auto">Fasilitas lengkap, Tempat nyaman, dan komunitas yang mendukungmu — semua dalam satu atap.</p>
        </div>

        {{-- Grid Fitur --}}
        <div class="grid md:grid-cols-3 gap-6 mb-24">
            @foreach([
                ['Peralatan Premium', 'Lebih dari 200+ peralatan gym terkini dari brand kelas dunia.'],
                ['Tempat Bersih dan Nyaman', 'Kebersihan peralatan dan ruang latihan yang nyaman.'],
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
        
        {{-- Kartu Paket (Dinamis - Simetris) --}}
        <div class="flex flex-wrap justify-center gap-8 items-center max-w-6xl mx-auto">
            @foreach($membershipPackages as $index => $package)
                @php
                    $isPopular = ($index === 1); 
                @endphp

                {{-- Class warna teks: jika populer tetap putih, jika tidak jadi gray-900 (hitam) --}}
                <div class="w-full md:w-[350px] {{ $isPopular ? 'relative p-10 bg-red-600 text-white rounded-3xl shadow-2xl scale-105 z-10' : 'p-8 bg-[#f5e6d3] rounded-3xl border border-gray-200' }}">
                    
                    @if($isPopular)
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 bg-black text-white text-[10px] font-black uppercase rounded-full">Terpopuler</div>
                    @endif

                    {{-- Label Paket --}}
                    <div class="text-[10px] font-black uppercase tracking-widest {{ $isPopular ? 'text-red-200' : 'text-gray-500' }} mb-2">Paket</div>
                    
                    {{-- Judul (Hitam jika tidak terpopuler) --}}
                    <h3 class="text-3xl font-black mb-4 {{ !$isPopular ? 'text-gray-900' : 'text-white' }}">
                        {{ $package->category }}
                    </h3>
                    
                    {{-- Harga (Hitam jika tidak terpopuler) --}}
                    <div class="text-4xl font-black mb-6 {{ !$isPopular ? 'text-gray-900' : 'text-white' }}">
                        Rp {{ number_format($package->price, 0, ',', '.') }} 
                        <span class="text-sm font-bold {{ $isPopular ? 'text-red-200' : 'text-gray-500' }}">/bulan</span>
                    </div>
                    
                    {{-- Benefit (Hitam jika tidak terpopuler) --}}
                    <div class="text-sm font-bold mb-8 {{ !$isPopular ? 'text-gray-800' : 'text-white' }}">
                        <div class="text-xs {{ !$isPopular ? 'text-gray-500' : 'opacity-80' }} mb-2">Benefit:</div>
                        <ul class="space-y-2">
                            @foreach(explode("\n", $package->description) as $desc)
                                <li>✓ {{ trim($desc) }}</li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <button class="w-full py-4 {{ $isPopular ? 'bg-white text-red-600' : 'bg-gray-900 text-white hover:bg-red-600' }} font-black rounded-lg">
                        PILIH PAKET {{ strtoupper($package->category) }}
                    </button>
                </div>
            @endforeach
        </div>

        {{-- Harga Harian/Guest (TARUH DI LUAR FOREACH AGAR TIDAK DUPLIKAT) --}}
        @if($dailyPrice)
            <div class="mt-16 text-center">
                <p class="text-gray-600 font-bold">
                    Hanya ingin latihan sekali? Kami punya 
                    <span class="text-red-600 font-black underline">{{ $dailyPrice->category }}</span> 
                    seharga <span class="font-black">Rp {{ number_format($dailyPrice->price, 0, ',', '.') }}</span>
                </p>
            </div>
        @endif
    </div>
</section>