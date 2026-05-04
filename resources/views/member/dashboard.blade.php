<x-app-layout>
    {{-- Tambahkan CDN Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Member Dashboard') }} 
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showWelcomeModal: {{ ($member?->status == 'inactive' || !$member) ? 'true' : 'false' }} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- MODAL POP-UP PEMBERITAHUAN (Tetap Ada) --}}
            <div x-show="showWelcomeModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
                
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">
                    <div class="w-20 h-20 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Selesaikan Membership!</h2>
                    <p class="text-gray-600 mb-6">
                        Akun kamu sudah terdaftar, namun status keanggotaanmu masih **Belum Aktif**. Selesaikan data dan lakukan pembayaran untuk menikmati fasilitas Piai Futsal Fitness.
                    </p>
                    
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('member.payments.create') }}" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition">
                            Lengkapi Data & Bayar Sekarang
                        </a>
                        <button @click="showWelcomeModal = false" class="text-gray-400 text-sm hover:text-gray-600">
                            Nanti Saja
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-8 text-gray-900">
                    <h1 class="text-2xl font-bold mb-2">Halo, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-gray-600 mb-8">Selamat datang di panel member Piai Futsal Fitness.</p>

                    {{-- GRID STATISTIK (Tetap Ada) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                        {{-- Status Keanggotaan --}}
                        <div class="p-6 bg-gradient-to-br from-blue-50 to-indigo-100 border border-blue-200 rounded-2xl shadow-sm">
                            <h3 class="text-blue-800 font-bold uppercase text-xs tracking-widest">Status Keanggotaan</h3>
                            <p class="text-3xl font-black mt-2 {{ ($member?->status == 'active') ? 'text-green-600' : 'text-red-600' }}">
                                {{ strtoupper($member?->status ?? 'BELUM DAFTAR') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-2 font-medium">Berlaku hingga: {{ $member?->membership_expiry ?? '-' }}</p>
                        </div>

                        {{-- Total Kehadiran --}}
                        <div class="p-6 bg-gradient-to-br from-purple-50 to-fuchsia-100 border border-purple-200 rounded-2xl shadow-sm">
                            <h3 class="text-purple-800 font-bold uppercase text-xs tracking-widest">Total Kehadiran</h3>
                            <p class="text-3xl font-black mt-2 text-purple-700">
                                {{ auth()->user()->presences?->count() ?? 0 }} <span class="text-lg font-normal text-purple-500">Sesi</span>
                            </p>
                            <a href="{{ route('member.presences.index') }}" class="text-xs text-purple-600 font-bold hover:underline mt-2 inline-block">Lihat Detail Riwayat →</a>
                        </div>

                        {{-- Aksi Cepat --}}
                        <div class="p-6 bg-gradient-to-br from-green-50 to-emerald-100 border border-green-200 rounded-2xl shadow-sm">
                            <h3 class="text-green-800 font-bold uppercase text-xs tracking-widest">Aksi Cepat</h3>
                            <div class="mt-4">
                                <a href="{{ route('member.payments.create') }}" class="w-full flex justify-center items-center px-4 py-3 bg-green-600 text-white text-sm font-black rounded-xl hover:bg-green-700 transition shadow-lg shadow-green-100">
                                    Bayar Iuran Bulanan
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- BAGIAN GRAFIK BARU (GOKIL ABIS) --}}
                    <div class="mt-8 p-8 bg-white border border-gray-100 rounded-3xl shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Progres Kehadiran</h3>
                                <p class="text-sm text-gray-500">Statistik latihan kamu dalam 7 hari terakhir</p>
                            </div>
                            <div class="hidden md:block">
                                <span class="px-4 py-2 bg-blue-50 text-blue-600 text-xs font-bold rounded-full">Grafik Mingguan</span>
                            </div>
                        </div>
                        
                        <div class="relative h-[300px]">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>

                    {{-- FOOTER (Tetap Ada) --}}
                    <div class="mt-10 pt-6 border-t border-gray-50">
                        <a href="{{ route('home') }}" class="text-sm text-blue-500 font-bold hover:text-blue-700 flex items-center gap-2 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Halaman Utama (Landing Page)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Inisialisasi Grafik --}}
    <script>
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        // Gradient color untuk grafik
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($days) !!},
                datasets: [{
                    label: 'Sesi Latihan',
                    data: {!! json_encode($counts) !!},
                    borderColor: '#3b82f6',
                    borderWidth: 4,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4, // Membuat garis melengkung keren
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { weight: 'bold' }
                        },
                        grid: { borderDash: [5, 5] }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: 'bold' } }
                    }
                }
            }
        });
    </script>
</x-app-layout>