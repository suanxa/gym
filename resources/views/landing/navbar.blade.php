<nav x-data="{ scrolled: false, open: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 50)"
     :class="scrolled ? 'bg-[#0f0d0d] border-b border-[#2a2a2a] py-3' : 'bg-transparent py-6'"
     class="fixed w-full z-50 px-6 lg:px-10 flex items-center justify-between transition-all duration-500 ease-in-out">
    
    {{-- LOGO & BRAND AREA --}}
<a href="#" class="flex items-center gap-4 group">
    {{-- Cek apakah $siteSetting ada agar tidak error --}}
    @if(isset($siteSetting))
        @if($siteSetting->logo)
            <img src="{{ asset('storage/' . $siteSetting->logo) }}" alt="Logo" class="h-8 w-8 rounded-lg object-cover">
        @else
            <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center text-white font-black text-sm">
                {{ substr($siteSetting->site_name ?? 'P', 0, 1) }}
            </div>
        @endif

        <div class="flex flex-col leading-none">
            @php
                $name = $siteSetting->site_name ?? 'PIAI FUTSAL';
                $words = explode(' ', $name);
                $first = array_shift($words);
                $rest = implode(' ', $words);
            @endphp
            
            <span class="text-xl font-black text-[#e8e6e1] uppercase tracking-tighter">
                {{ $first }}
            </span>
            
            @if($rest)
                <span class="text-[10px] text-red-600 font-bold uppercase tracking-[0.2em]">
                    {{ $rest }}
                </span>
            @endif
        </div>
    @else
        {{-- Fallback jika database belum disetting --}}
        <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center text-white font-black text-sm">P</div>
        <span class="text-xl font-black text-[#e8e6e1] uppercase">PIAI</span>
    @endif
</a>

    {{-- HAMBURGER BUTTON --}}
    <button @click="open = !open" class="lg:hidden text-[#e8e6e1] focus:outline-none">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
    </button>

    {{-- MENU UTAMA (Spy Scroll + Mobile Logic) --}}
    <div x-data="{ activeMenu: 'home' }" 
         @scroll.window.window="
            let sections = ['home', 'kesehatan', 'fitur', 'ulasan', 'tentang-kami'];
            let scrollPos = window.pageYOffset + 150;
            sections.forEach(section => {
                let el = document.getElementById(section);
                if (el && el.offsetTop <= scrollPos && (el.offsetTop + el.offsetHeight) > scrollPos) {
                    activeMenu = section;
                }
            });
         "
         :class="open ? 'flex absolute top-full left-0 w-full bg-[#0f0d0d] flex-col p-6 border-b border-[#2a2a2a]' : 'hidden lg:flex'"
         class="lg:flex-grow lg:justify-center lg:items-center lg:gap-8">
         
        @foreach(['HOME', 'KESEHATAN', 'FITUR', 'ULASAN', 'TENTANG KAMI'] as $menu)
            @php $slug = strtolower(str_replace(' ', '-', $menu)); if($slug == 'tentang') $slug = 'tentang'; @endphp
            <a href="#{{ $slug }}" 
               @click="activeMenu = '{{ $slug }}'; open = false;"
               :class="activeMenu === '{{ $slug }}' ? 'bg-[#2a1111] text-red-500' : 'text-[#e8e6e1] hover:text-red-500'"
               class="py-4 lg:py-2 px-6 rounded-lg text-[13px] font-black uppercase tracking-wider transition-all text-center">
                {{ $menu }}
            </a>
        @endforeach
    </div>

    {{-- LOGIN BUTTON --}}
    <div class="hidden lg:flex items-center gap-3">
        <a href="{{ route('login') }}" class="px-8 py-2.5 border border-[#444] text-[#e8e6e1] text-[12px] font-black uppercase tracking-widest rounded-lg hover:border-red-600 transition">LOGIN</a>
    </div>
</nav>