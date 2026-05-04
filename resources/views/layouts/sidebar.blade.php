<aside id="logo-sidebar" class="fixed top-0 left-0 z-30 w-64 h-screen pt-20 transition-transform -translate-x-full bg-gray-800 border-r border-gray-700 sm:translate-x-0 dark:bg-gray-800" aria-label="Sidebar">
   <div class="h-full px-3 pb-4 overflow-y-auto bg-gray-800">
      <ul class="space-y-2 font-medium">
         
         {{-- MENU UNTUK ADMIN --}}
         @if(auth()->user()->role == 'admin')
            <li class="text-gray-400 text-xs uppercase px-3 py-2">Menu Utama</li>
            <li>
               <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 group {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                  <span class="ms-3">Dashboard</span>
               </a>
            </li>

            <li>
                <a href="{{ route('admin.members.index') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 group {{ request()->routeIs('admin.members.*') ? 'bg-gray-700' : '' }}">
                    <span class="ms-3">Data Member</span>
                </a>
            </li>

            <li class="text-gray-400 text-xs uppercase px-3 py-2 mt-4">Transaksi & Fasilitas</li>
            <li>
               <a href="{{ route('admin.payments.index') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 group {{ request()->routeIs('admin.payments.*') ? 'bg-gray-100 bg-opacity-20' : '' }}">
                  <span class="ms-3">Pembayaran</span>
               </a>
            </li>

            <li>
               <a href="{{ route('admin.payments.harga') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 group {{ request()->routeIs('admin.payments.*') ? 'bg-gray-100 bg-opacity-20' : '' }}">
                  <span class="ms-3">Harga</span>
               </a>
            </li>

            <li>
               <a href="{{ route('admin.expenses.index') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 group {{ request()->routeIs('admin.expenses.*') ? 'bg-gray-100 bg-opacity-20' : '' }}">
                  <span class="ms-3">Catatan Pengeluaran</span>
               </a>
            </li>

            <li class="text-gray-400 text-xs uppercase px-3 py-2 mt-4">Konten Web</li>
            <li>
               <a href="{{ route('admin.reviews.index') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 group {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-100 bg-opacity-20' : '' }}">
                  <span class="ms-3">Kelola Review</span>
               </a>
            </li>

            <li>
               <a href="{{ route('admin.settings.index') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 group {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-100 bg-opacity-20' : '' }}">
                  <span class="ms-3">Setting website</span>
               </a>
            </li>

         {{-- MENU UNTUK MEMBER --}}
         @elseif(auth()->user()->role == 'member')
            <li class="text-gray-400 text-xs uppercase px-3 py-2">Panel Member</li>
            <li>
               <a href="{{ route('member.dashboard') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 group {{ request()->routeIs('member.dashboard') ? 'bg-gray-700' : '' }}">
                  <span class="ms-3">Dashboard</span>
               </a>
            </li>

            <li>
               <a href="{{ route('member.payments.create') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-gray-700 group {{ request()->routeIs('member.payments.*') ? 'bg-gray-700' : '' }}">
                  <span class="ms-3">Pembayaran Member</span>
               </a>
            </li>

            <li>
               <a href="{{ route('member.presences.index') }}" class="flex items-center justify-between p-2 text-white rounded-lg hover:bg-gray-700 group {{ request()->routeIs('member.presences.*') ? 'bg-gray-700' : '' }}">
                  <span class="ms-3">Status Member</span>
                  
                  {{-- Indikator Badge Active --}}
                  @if(auth()->user()->member?->status === 'active')
                     <span class="inline-flex items-center justify-center px-2 py-0.5 ms-3 text-xs font-bold text-green-400 bg-green-900 border border-green-700 rounded-full animate-pulse">
                        ACTIVE
                     </span>
                  @endif
               </a>
            </li>
         @endif

         {{-- LOGOUT --}}
         <li class="pt-4 mt-4 border-t border-gray-700">
            <form method="POST" action="{{ route('logout') }}">
               @csrf
               <button type="submit" class="flex items-center w-full p-2 text-red-400 transition duration-75 rounded-lg group hover:bg-red-900 hover:text-white">
                  <span class="ms-3 text-sm font-bold uppercase">Keluar Sistem</span>
               </button>
            </form>
         </li>

      </ul>
   </div>
</aside>