{{-- [PERBAIKAN]: x-data dan x-init dinaikkan ke level paling atas tag <nav> agar seluruh komponen membaca serentak --}}
<nav x-data="{ 
    open: false,
    darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
}" 
x-init="
    $watch('darkMode', val => {
        if (val) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        }
    });
    if (darkMode) document.documentElement.classList.add('dark');
    else document.documentElement.classList.remove('dark');
"
class="bg-white dark:bg-gray-950/90 backdrop-blur-md border-b border-gray-100 dark:border-gray-900 fixed w-full z-50 transition-all duration-200">
    
    {{-- INJEKSI STYLE DINAMIS UNTUK BODY KONTEN --}}
    <style>
        .dark body, .dark main, .dark .min-h-screen { 
            background-color: #030712 !important; 
        }
        body, main, .min-h-screen { 
            background-color: #f3f4f6 !important; 
            transition: background-color 0.2s ease;
        }
    </style>

    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                {{-- AREA LOGO --}}
                <div class="shrink-0 flex items-center">
                    @php
                        $dashboardRoute = auth()->user()->role === 'admin' ? 'admin.dashboard' : 'member.dashboard';
                        $siteSetting = \App\Models\Setting::first();
                    @endphp
                    
                    <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-2.5 group">
                        @if($siteSetting && $siteSetting->logo)
                            <img src="{{ asset('storage/' . $siteSetting->logo) }}" class="block h-9 w-auto rounded-lg transition duration-200 group-hover:scale-105">
                        @else
                            <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center text-white font-black shadow-md shadow-red-600/40 transition duration-200 group-hover:scale-105">
                                P
                            </div>
                        @endif
                        <span class="text-sm font-black tracking-wider text-gray-900 dark:text-white transition duration-200 group-hover:text-red-600">
                            PIAI <span class="text-red-600">WELLNESS</span>
                        </span>
                    </a>
                </div>

                {{-- INDIKATOR PANEL --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <span class="inline-flex items-center px-1 pt-1 text-xs font-bold tracking-widest uppercase text-gray-400 dark:text-gray-500">
                        <span class="w-2 h-2 rounded-full bg-red-600 me-2 animate-pulse"></span>
                        {{ auth()->user()->role === 'admin' ? 'Admin Control' : 'Member Lounge' }}
                    </span>
                </div>
            </div>

            {{-- MENU KANAN (DROPDOWN + SWITCHER DESKTOP) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                
                {{-- TOMBOL SWITCHER THEME --}}
                <button @click="darkMode = !darkMode" type="button" class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 transition duration-200 focus:outline-none" title="Ganti Tema Visual">
                    <svg x-show="darkMode" class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 2.293a1 1 0 011.414 0l.707.707a1 1 0 01-1.414 1.414l-.707-.707a1 1 0 010-1.414zm4 4.707a1 1 0 011 1v1a1 1 0 11-2 0V10a1 1 0 011-1zm-2.293 4a1 1 0 010 1.414l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 0zM10 16a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM4.586 14.414a1 1 0 010-1.414l.707-.707a1 1 0 011.414 1.414l-.707.707a1 1 0 01-1.414 0zM2 10a1 1 0 011-1h1a1 1 0 110 2H3a1 1 0 01-1-1zm1.293-4.707a1 1 0 011.414-1.414l.707.707a1 1 0 01-1.414 1.414l-.707-.707zM10 5a5 5 0 100 10 5 5 0 000-10z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                    <svg x-show="!darkMode" class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </button>

                {{-- DROPDOWN USER --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1.5 border border-gray-200 dark:border-gray-800 text-sm font-semibold rounded-xl text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div class="text-red-600 dark:text-red-500 font-bold tracking-wide">{{ Auth::user()->name }}</div>
                            <div class="ms-1.5 text-gray-400 dark:text-gray-500">
                                <svg class="fill-current h-4 w-4 transition-transform duration-200" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden shadow-xl shadow-black/10 dark:shadow-xl dark:shadow-black/50">
                            <x-dropdown-link :href="route('profile.edit')" class="text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 hover:text-gray-900 dark:hover:text-white transition duration-150">
                                <span class="flex items-center font-medium"><i class="w-2 h-2 rounded-full bg-gray-400 dark:bg-gray-600 me-2"></i> Pengaturan Akun</span>
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" class="text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 hover:text-red-700 dark:hover:text-red-300 transition duration-150" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <span class="flex items-center font-bold uppercase text-xs tracking-wider"><i class="w-2 h-2 rounded-full bg-red-600 me-2"></i> {{ __('Log Out') }}</span>
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- MENU MOBILE --}}
            <div class="-me-2 flex items-center sm:hidden gap-2">
                <button @click="darkMode = !darkMode" type="button" class="p-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 focus:outline-none">
                    <svg x-show="darkMode" class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 5a5 5 0 100 10 5 5 0 000-10z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    <svg x-show="!darkMode" class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                </button>

                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none transition duration-150 ease-in-out border border-transparent hover:border-gray-200 dark:hover:border-gray-800">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- INTERFACE DROPDOWN MOBILE --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100 dark:border-gray-900 bg-white/95 dark:bg-gray-950/95 backdrop-blur-md">
        <div class="pt-3 pb-3 space-y-1 border-b border-gray-100 dark:border-gray-900">
            <div class="px-4 py-1.5 text-[11px] font-black uppercase text-gray-400 dark:text-gray-500 tracking-wider flex items-center">
                <span class="w-2 h-2 bg-red-600 rounded-full me-2"></span> Navigasi akun kamu
            </div>
            <div class="mt-2 space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl text-gray-700 dark:text-gray-300 hover:text-gray-900 hover:text-white hover:bg-gray-100 dark:hover:bg-gray-900 border-none font-semibold text-sm pl-4">
                    {{ __('Edit Profile') }}
                </x-responsive-nav-link>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" class="rounded-xl text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-950/20 border-none font-bold text-sm pl-4 uppercase tracking-wider" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>