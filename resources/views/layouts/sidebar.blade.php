{{-- UTILITY STYLE OVERRIDE (ANTI-GAGAL: Memaksa perubahan warna sidebar lewat CSS murni) --}}
<style>
    /* Mode Terang (Default untuk cetak laporan TA) */
    #logo-sidebar, #logo-sidebar div {
        background-color: #ffffff !important;
        border-color: #e5e7eb !important;
        transition: all 0.2s ease;
    }
    #logo-sidebar .menu-title {
        color: #6b7280 !important; /* Abu-abu teks formal */
    }
    #logo-sidebar a:not(.bg-red-600) {
        color: #374151 !important; /* Teks menu pasif gelap agar terbaca */
    }
    #logo-sidebar a:not(.bg-red-600):hover {
        background-color: #f3f4f6 !important;
        color: #000000 !important;
    }
    #logo-sidebar svg:not(.text-white) {
        color: #4b5563 !important;
    }

    /* Mode Gelap (Otomatis aktif saat class .dark ada di root HTML) */
    .dark #logo-sidebar, .dark #logo-sidebar div {
        background-color: #030712 !important; /* Kembali ke Hitam Pejantan */
        border-color: #111827 !important;
    }
    .dark #logo-sidebar .menu-title {
        color: #4b5563 !important;
    }
    .dark #logo-sidebar a:not(.bg-red-600) {
        color: #9ca3af !important;
    }
    .dark #logo-sidebar a:not(.bg-red-600):hover {
        background-color: #1f2937 !important;
        color: #ffffff !important;
    }
    .dark #logo-sidebar svg:not(.text-white) {
        color: #6b7280 !important;
    }
</style>

{{-- 1. TOMBOL TOGGLE MOBILE --}}
<button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2.5 text-sm text-gray-500 dark:text-gray-400 rounded-xl sm:hidden hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-red-600 fixed top-3 right-4 z-50 bg-white/90 dark:bg-gray-950/90 backdrop-blur-md border border-gray-200 dark:border-gray-800 shadow-lg transition-all duration-200">
   <span class="sr-only">Buka Menu Navigasi</span>
   <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

{{-- 2. ASIDE SIDEBAR LAYER --}}
<aside id="logo-sidebar" class="fixed top-0 left-0 w-64 h-screen pt-20 transition-transform -translate-x-full border-r sm:translate-x-0 z-40 sm:z-20" aria-label="Sidebar">
   <div class="h-full px-4 pb-4 overflow-y-auto custom-scrollbar pt-2">
      
      {{-- BRAND LOGO AREA (Dinamis dari Database) --}}
<div class="flex items-center justify-center mb-5 ps-2 sm:hidden border-b border-gray-100 dark:border-gray-900 pb-4 mt-6">
    <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-2">
        @if($siteSetting && $siteSetting->logo)
            <img src="{{ asset('storage/' . $siteSetting->logo) }}" class="h-9 w-auto rounded-lg">
        @else
            <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center text-white font-black shadow-md shadow-red-600/40">
                {{ substr($siteSetting->site_name ?? 'P', 0, 1) }}
            </div>
        @endif
        <span class="text-sm font-black tracking-wider text-gray-900 dark:text-white uppercase">
            {{ $siteSetting->site_name ?? 'PIAI WELLNESS' }}
        </span>
    </a>
</div>

      <ul class="space-y-1.5 font-medium">
         
         {{-- MENU UNTUK ADMIN --}}
         @if(auth()->user()->role == 'admin')
            <li class="flex items-center menu-title text-[11px] font-bold uppercase tracking-wider px-3 pt-1 pb-1">
               <span>Menu Utama</span>
            </li>
            
            {{-- DASHBOARD --}}
            <li>
               <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : '' }}">
                  <svg class="w-5 h-5 transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                     <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039 1 1 0 0 0-1-.066h.002Z"/>
                     <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
                  </svg>
                  <span class="ms-3.5">Dashboard Overview</span>
               </a>
            </li>

            {{-- DATA MEMBER --}}
            <li>
               <a href="{{ route('admin.members.index') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.members.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : '' }}">
                  <svg class="w-5 h-5 transition duration-200 {{ request()->routeIs('admin.members.*') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                     <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
                  </svg>
                  <span class="ms-3.5">Kelola Member</span>
               </a>
            </li>

            <li class="flex items-center menu-title text-[11px] font-bold uppercase tracking-wider px-3 pt-4 pb-1">
               <span>Transaksi & Keuangan</span>
            </li>
            
            {{-- PEMBAYARAN --}}
            <li>
               <a href="{{ route('admin.payments.index') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.payments.index') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : '' }}">
                  <svg class="w-5 h-5 transition duration-200 {{ request()->routeIs('admin.payments.index') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                     <path d="M11.074 4 8.442.408A1 1 0 0 0 7.641 0H2.419A2.422 2.422 0 0 0 0 2.419v15.162A2.422 2.422 0 0 0 2.419 20h15.162A2.422 2.422 0 0 0 20 17.581V4h-8.926ZM12 13H4v-2h8v2Zm4-4H4V7h12v2Z"/>
                  </svg>
                  <span class="ms-3.5">Transaksi Member / Guest</span>
               </a>
            </li>

            {{-- KELOLA HARGA PAKET --}}
            <li>
               <a href="{{ route('admin.payments.harga') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.payments.harga') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : '' }}">
                  <svg class="w-5 h-5 transition duration-200 {{ request()->routeIs('admin.payments.harga') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                     <path d="m11.166 11.127-1.121 1.117a1 1 0 0 1-1.414 0l-5.45-5.426a1 1 0 0 1 0-1.414l1.121-1.117a1 1 0 0 1 1.414 0l5.45 5.426a1 1 0 0 1 0 1.414Zm4.5-4.5-1.121 1.117a1 1 0 0 1-1.414 0l-5.45-5.426a1 1 0 0 1 0-1.414l1.121-1.117a1 1 0 0 1 1.414 0l5.45 5.426a1 1 0 0 1 0 1.414Z"/>
                     <path d="M16 19a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/>
                  </svg>
                  <span class="ms-3.5">Konfigurasi Harga</span>
               </a>
            </li>

            {{-- CATATAN PENGELUARAN --}}
            <li>
               <a href="{{ route('admin.expenses.index') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.expenses.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : '' }}">
                  <svg class="w-5 h-5 transition duration-200 {{ request()->routeIs('admin.expenses.*') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                     <path d="M7 9a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                     <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-6-4h4V4h-4v2Zm0 4h4V8h-4v2Zm4 4h-4v-2h4v2Z" clip-rule="evenodd"/>
                  </svg>
                  <span class="ms-3.5">Log Pengeluaran</span>
               </a>
            </li>

            <li class="flex items-center menu-title text-[11px] font-bold uppercase tracking-wider px-3 pt-4 pb-1">
               <span>Manajemen Konten</span>
            </li>
            
            {{-- KELOLA REVIEW --}}
            <li>
               <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.reviews.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : '' }}">
                  <svg class="w-5 h-5 transition duration-200 {{ request()->routeIs('admin.reviews.*') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                     <path d="M18 0H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h3.546l3.2 3.659a1 1 0 0 0 1.508 0l3.2-3.659H18a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-8 10H4V8h6v2Zm6-4H4V4h12v2Z"/>
                  </svg>
                  <span class="ms-3.5">Moderasi Ulasan</span>
               </a>
            </li>

            {{-- SETTING WEBSITE --}}
            <li>
               <a href="{{ route('admin.settings.index') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.settings.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 hover:text-gray-900 dark:hover:text-white' }}">
                  <svg class="w-5 h-5 transition duration-200 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-900 dark:group-hover:text-gray-300' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                     <path fill-rule="evenodd" d="M11.49 3.17c-.1-.74-.74-1.3-1.49-1.3s-1.39.56-1.49 1.3l-.13.98c-.46.18-.89.43-1.29.74l-.9-.4a1.496 1.496 0 0 0-1.92.51l-1 1.73c-.37.64-.2 1.46.41 1.9l.78.56c-.03.24-.05.49-.05.74s.02.5.05.74l-.78.56c-.61.44-.78 1.26-.41 1.9l1 1.73c.37.63 1.15.85 1.92.51l.9-.4c.4.31.83.56 1.29.74l.13.98c.1.74.74 1.3 1.49 1.3s1.39-.56 1.49-1.3l.13-.98c.46-.18.89-.43 1.29-.74l.9.4a1.496 1.496 0 0 0 1.92-.51l1-1.73c.37-.64.2-1.46-.41-1.9l-.78-.56c.03-.24.05-.49.05-.74s-.02-.5-.05-.74l.78-.56c.61-.44.78-1.26.41-1.9l-1-1.73a1.495 1.495 0 0 0-1.92-.51l-.9.4c-.4-.31-.83-.56-1.29-.74l-.13-.98ZM10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd"/>
                  </svg>
                  <span class="ms-3.5">Identitas Usaha</span>
               </a>
            </li>

            <li>
               <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900' }}">
                  <svg class="w-5 h-5 transition duration-200 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-gray-400' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                     <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                  </svg>
                  <span class="ms-3.5">Manajemen Admin</span>
               </a>
            </li>
         {{-- MENU UNTUK MEMBER --}}
         @elseif(auth()->user()->role == 'member')
            <li class="flex items-center menu-title text-[11px] font-bold uppercase tracking-wider px-3 pt-1 pb-1">
               <span>Panel Member Area</span>
            </li>
            
            <li>
               <a href="{{ route('member.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('member.dashboard') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : '' }}">
                  <svg class="w-5 h-5 transition duration-200 {{ request()->routeIs('member.dashboard') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                     <path d="M10.707 2.293a1 1 0 0 0-1.414 0l-7 7a1 1 0 0 0 1.414 1.414L4 10.414V17a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-6.586l1.293 1.293a1 1 0 0 0 1.414-1.414l-7-7Z"/>
                  </svg>
                  <span class="ms-3.5">Dashboard Kamu</span>
               </a>
            </li>

            <li>
               <a href="{{ route('member.payments.create') }}" class="flex items-center px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('member.payments.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : '' }}">
                  <svg class="w-5 h-5 transition duration-200 {{ request()->routeIs('member.payments.*') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                     <path d="M18 0H2A2 2 0 0 0 0 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2ZM2 2h16v2H2V2Zm16 10H2V6h16v6Z"/>
                  </svg>
                  <span class="ms-3.5">Konfirmasi Bayar</span>
               </a>
            </li>

            <li>
               <a href="{{ route('member.presences.index') }}" class="flex items-center justify-between px-3 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('member.presences.*') ? 'bg-red-600 text-white shadow-lg shadow-red-600/30' : '' }}">
                  <div class="flex items-center">
                     <svg class="w-5 h-5 transition duration-200 {{ request()->routeIs('member.presences.*') ? 'text-white' : '' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-7h2v2H5v-2Z"/>
                     </svg>
                     <span class="ms-3.5">Status Keanggotaan</span>
                  </div>
                  
                  @if(auth()->user()->member?->status === 'active')
                     <span class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold text-red-600 bg-red-500/10 border border-red-500/30 rounded-full shadow-sm shadow-red-500/20 tracking-wider animate-pulse">
                        ACTIVE
                     </span>
                  @endif
               </a>
            </li>
         @endif

         {{-- LOGOUT BUTTON --}}
         <li class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-900">
            <form method="POST" action="{{ route('logout') }}">
               @csrf
               <button type="submit" class="flex items-center w-full px-4 py-2.5 text-sm font-bold tracking-wide text-red-600 transition-all duration-200 rounded-xl group hover:bg-red-500/10 hover:text-red-400 focus:outline-none">
                  <svg class="w-5 h-5 text-red-500 transition duration-200 group-hover:translate-x-1" aria-hidden="true" xmlns="http://www.w3.org/2000/xl" fill="none" viewBox="0 0 18 16">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
                  </svg>
                  <span class="ms-4 uppercase text-[12px]">Keluar Sistem</span>
               </button>
            </form>
         </li>

      </ul>
   </div>
</aside>

<style>
   .custom-scrollbar::-webkit-scrollbar { width: 5px; }
   .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
   .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
   .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #1f2937; }
</style>