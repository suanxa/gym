@php
    $siteSetting = \App\Models\Setting::first();
@endphp

<footer class="bg-gray-950 text-white py-10 px-10">
    <div class="max-w-7xl mx-auto grid lg:grid-cols-4 gap-12">
        
        {{-- Kolom 1: Brand & Socials --}}
        <div class="lg:col-span-1 space-y-6">
            <h3 class="text-3xl font-black uppercase tracking-tighter text-red-600">
                {{ $siteSetting->site_name ?? 'PIAI FITNESS' }}
            </h3>
            <p class="text-gray-400 text-sm leading-relaxed">
                {{ $siteSetting->description ?? 'Pusat kebugaran terbaik untuk membentuk tubuh dan kesehatan optimal.' }}
            </p>
            {{-- Link Instagram --}}
            <a href="https://www.instagram.com/piaifutsalfitness" target="_blank" 
               class="inline-flex items-center gap-2 bg-white/5 hover:bg-red-600 transition-colors px-4 py-2 rounded-full text-xs font-bold uppercase tracking-widest">
                <span class="text-red-500">IG:</span> @piaifutsalfitness
            </a>
        </div>

        {{-- Kolom 2: Hubungi Kami --}}
        <div>
            <h4 class="font-black uppercase tracking-widest text-xs mb-8 text-white border-l-2 border-red-600 pl-4">Hubungi Kami</h4>
            <ul class="text-gray-400 text-sm space-y-4">
                <li class="flex flex-col">
                    <span class="text-red-600 text-[10px] font-bold uppercase mb-1">Telepon</span>
                    <a href="tel:{{ $siteSetting->phone ?? '#' }}" class="hover:text-white">{{ $siteSetting->phone ?? '-' }}</a>
                </li>
                <li class="flex flex-col">
                    <span class="text-red-600 text-[10px] font-bold uppercase mb-1">Email</span>
                    <a href="mailto:{{ $siteSetting->email ?? '#' }}" class="hover:text-white">{{ $siteSetting->email ?? '-' }}</a>
                </li>
            </ul>
        </div>

        {{-- Kolom 3: Lokasi --}}
        <div>
            <h4 class="font-black uppercase tracking-widest text-xs mb-8 text-white border-l-2 border-red-600 pl-4">Lokasi</h4>
            <p class="text-gray-400 text-sm leading-relaxed">
                {{ $siteSetting->address ?? 'Lokasi gym belum diatur.' }}
            </p>
        </div>
    </div>

    {{-- Bottom Footer --}}
    <div class="max-w-7xl mx-auto mt-10 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] font-bold uppercase tracking-widest text-gray-500">
        <p>&copy; {{ date('Y') }} {{ $siteSetting->site_name ?? 'PIAI FITNESS' }}. All rights reserved.</p>
        <p>System Version: <span class="text-gray-300">V1.0</span></p>
    </div>
</footer>