<section id="tentang-kami" class="py-24 px-10 bg-[#f5e6d3] text-gray-900">
    <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-start">
        
        {{-- Sisi Kiri: Visual Collage + Maps --}}
        <div class="space-y-8">
            {{-- Grid Gambar --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="row-span-2 relative">
                    <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=600" class="w-full h-full object-cover rounded-3xl shadow-xl">
                    <div class="absolute -bottom-6 -left-6 bg-red-600 text-white p-6 rounded-2xl shadow-xl rotate-[-5deg]">
                        <div class="text-4xl font-black">2021</div>
                        <div class="text-[9px] font-bold uppercase tracking-widest">BERDIRI SEJAK</div>
                    </div>
                </div>
                <div class="space-y-4">
                    <img src="https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?q=80&w=400" class="w-full h-48 object-cover rounded-3xl shadow-lg">
                    <img src="https://images.pexels.com/photos/1552242/pexels-photo-1552242.jpeg?auto=compress&cs=tinysrgb&w=400" class="w-full h-48 object-cover rounded-3xl shadow-lg" alt="Latihan Gym">
                </div>
            </div>

            {{-- Maps Pindah ke bawah Gambar --}}
            <div class="group relative overflow-hidden rounded-2xl shadow-lg border border-gray-200">
                <a href="https://maps.app.goo.gl/XXXXXXXXXXXX" target="_blank" class="block w-full h-48 relative">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3464.6485189841974!2d100.4248273742636!3d-0.9407104353445932!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd4b93ec95961a9%3A0x7f9c948817535d0c!2sPiai%20Fitnes!5e1!3m2!1sid!2sid!4v1784212716979!5m2!1sid!2sid" class="w-full h-full pointer-events-none" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    <div class="absolute inset-0 bg-red-600/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="bg-white px-4 py-2 font-black text-xs uppercase tracking-widest text-red-600 rounded-full shadow-lg">Buka di Maps →</span>
                    </div>
                </a>
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

            <div class="grid grid-cols-2 gap-8 mb-10 border-t border-gray-300 pt-8">
                <div>
                    <div class="text-3xl font-black text-gray-900">5+</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-gray-500">Tahun Aktif</div>
                </div>
                <div>
                    <div class="text-3xl font-black text-gray-900">50+</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-gray-500">Member Aktif</div>
                </div>
                <div>
                    <div class="text-3xl font-black text-gray-900">50+</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-gray-500">Kelas Per Bulan</div>
                </div>
            </div>
        </div>
    </div>
</section>