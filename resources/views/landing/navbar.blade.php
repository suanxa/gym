<nav x-data="{ scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 50)"
     :class="scrolled ? 'bg-[#0f0d0d] border-b border-[#2a2a2a] py-3' : 'bg-transparent py-6'"
     class="fixed w-full z-50 px-10 flex items-center transition-all duration-500 ease-in-out">
    
    {{-- LOGO & BRAND AREA --}}
    <a href="#" class="flex items-center gap-4 group">
        @if($siteSetting && $siteSetting->logo)
            <img src="{{ asset('storage/' . $siteSetting->logo) }}" alt="Logo" class="h-10 w-10 rounded-lg object-cover">
        @else
            <div class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center text-white font-black text-lg">
                {{ substr($siteSetting->site_name ?? 'P', 0, 1) }}
            </div>
        @endif

        <div class="flex flex-col">
            @php
                $siteName = $siteSetting->site_name ?? 'PIAI FUTSAL FITNESS';
                $parts = explode(' ', $siteName);
                $first = array_shift($parts);
                $remaining = implode(' ', $parts);
            @endphp
            <span class="text-2xl font-black text-[#e8e6e1] uppercase tracking-tighter leading-none">{{ $first }}</span>
            <span class="text-[10px] text-red-600 font-bold uppercase tracking-[0.25em] leading-tight">{{ $remaining }}</span>
        </div>
    </a>

    {{-- MENU UTAMA --}}
    <div class="flex-grow flex justify-center items-center gap-8">
        @foreach(['HOME', 'KESEHATAN', 'FITUR', 'ULASAN', 'TENTANG KAMI'] as $menu)
            <a href="#{{ strtolower(str_replace(' ', '-', $menu)) }}" 
               class="{{ $loop->first ? 'bg-[#2a1111] text-red-500' : 'text-[#e8e6e1] hover:text-red-500' }} 
                      px-6 py-2 rounded-lg text-[13px] font-black uppercase tracking-wider transition-all">
                {{ $menu }}
            </a>
        @endforeach
    </div>

    {{-- ACTION AREA (Tanpa Register) --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('login') }}" 
           class="px-8 py-2.5 border border-[#444] text-[#e8e6e1] text-[12px] font-black uppercase tracking-widest rounded-lg hover:border-red-600 hover:text-red-600 transition">
            LOGIN
        </a>
    </div>
</nav>