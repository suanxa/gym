<x-app-layout>
    {{-- INJEKSI STYLE DINAMIS: Menghapus properti static !important lama agar sinkron dengan tombol navigasi --}}
    <style>
        .dark body, .dark main, .dark .min-h-screen { 
            background-color: #030712 !important; /* bg-gray-950 */
        }
        body, main, .min-h-screen { 
            background-color: #f3f4f6 !important; /* bg-gray-100 formal untuk cetak laporan TA */
            transition: background-color 0.2s ease;
        }
    </style>

    {{-- Ambil data statistik riil langsung dari database --}}
    @php
        $totalMembers = \App\Models\Member::count();
        $activeMembers = \App\Models\Member::where('status', 'active')->count();
        $pendingPayments = \App\Models\Payment::where('status', 'pending')->count();
        $totalRevenue = \App\Models\Payment::where('status', 'approved')->sum('amount');
        $pendingReviews = \App\Models\Review::where('is_published', false)->count();
        
        // --- LOGIC PENYUNTIKAN DATA FALLBACK JIKA PRESENSI MASIH MINIM ---
        $chartFallback = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateKey = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');
            $dateLabel = \Carbon\Carbon::now()->subDays($i)->format('d M');
            $chartFallback[$dateKey] = [
                'label' => $dateLabel,
                'total' => 0
            ];
        }

        $realPresences = \DB::table('presences')
            ->select(\DB::raw('DATE(check_in) as date'), \DB::raw('count(*) as total'))
            ->where('check_in', '>=', \Carbon\Carbon::now()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->get();

        foreach ($realPresences as $presence) {
            if (isset($chartFallback[$presence->date])) {
                $chartFallback[$presence->date]['total'] = $presence->total;
            }
        }

        $chartLabels = array_column($chartFallback, 'label');
        $chartData = array_column($chartFallback, 'total');

        $todayPresences = \App\Models\Presence::with(['user.member'])
        ->whereDate('check_in', \Carbon\Carbon::today())
        ->latest()
        ->get();
    @endphp

    {{-- [FIXED MARGIN & DYNAMIC BG]: Menggunakan bg-gray-100 untuk light mode, dan dark:bg-gray-950 untuk dark mode --}}
    <div class="p-4 sm:ml-1 bg-gray-100 dark:bg-gray-950 min-h-screen pt-2 text-gray-800 dark:text-gray-100 transition-colors duration-200">
        <div class="max-w-7xl mx-auto space-y-6">
            
            {{-- HEADER BANNER DYNAMIC --}}
            <div class="p-6 bg-gradient-to-r from-white via-slate-50 to-red-50 dark:from-gray-950 dark:via-gray-900 dark:to-red-950/40 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-md dark:shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black tracking-wide text-gray-900 dark:text-white flex items-center gap-2">
                        Selamat Datang, <span class="text-red-600 dark:text-red-500">{{ auth()->user()->name }}</span>!
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sistem Kontrol Operasional & Verifikasi Anggota Piai Futsal Fitness.</p>
                </div>
                <div class="flex items-center gap-2 bg-white dark:bg-gray-950 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span>
                    <span class="text-xs font-mono font-bold text-gray-600 dark:text-gray-300 tracking-wider">SYSTEM STATUS: ONLINE</span>
                </div>
            </div>

            {{-- 4 STATS GRID (CARDS JADI RESPONSIF DUAL MODE) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                {{-- CARD 1 --}}
                <div class="p-5 bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm transition duration-200 hover:border-red-500 dark:hover:border-red-600/50 group backdrop-blur-sm">
                    <div class="flex justify-between items-center">
                        <p class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider">Total Anggota</p>
                        <div class="p-2 bg-red-50 dark:bg-red-600/10 text-red-600 dark:text-red-500 rounded-xl group-hover:bg-red-600 group-hover:text-white transition duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 0 0 6 0zM18 8a2 2 0 11-4 0 2 2 0 0 1 4 0zM14 15a4 4 0 0 0-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 0 1 4 0zM16 18v-3a5.972 5.972 0 0 0-.75-2.906A6.005 6.005 0 0 1 19 15v3h-3zM4.75 12.094A5.973 5.973 0 0 0 4 15v3H1v-3a3 3 0 0 1 3.75-2.906z"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black mt-2 text-gray-900 dark:text-white">{{ $totalMembers }} <span class="text-xs text-green-600 dark:text-green-500 font-normal">({{ $activeMembers }} Aktif)</span></p>
                    <a href="{{ route('admin.members.index') }}" class="text-[11px] text-red-600 dark:text-red-500 hover:underline font-bold block mt-3 uppercase tracking-wider">Buka database &rarr;</a>
                </div>

                {{-- CARD 2 --}}
                <div class="p-5 bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm transition duration-200 hover:border-red-500 dark:hover:border-red-600/50 group backdrop-blur-sm">
                    <div class="flex justify-between items-center">
                        <p class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider">Butuh Konfirmasi</p>
                        <div class="p-2 bg-yellow-50 dark:bg-yellow-600/10 text-yellow-600 dark:text-yellow-500 rounded-xl group-hover:bg-yellow-500 group-hover:text-black transition duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 0 0-2 2v1h16V6a2 2 0 0 0-2-2H4zM18 9H2v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9zM4 13a1 1 0 0 1 1-1h1a1 1 0 1 1 0 2H5a1 1 0 0 1-1-1zm5-1a1 1 0 1 0 0 2h3a1 1 0 1 0 0-2H9z"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black mt-2 text-gray-900 dark:text-white flex items-center gap-2">
                        {{ $pendingPayments }}
                        @if($pendingPayments > 0)
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold bg-red-600 text-white rounded-full animate-pulse">BARU</span>
                        @endif
                    </p>
                    <a href="{{ route('admin.payments.index') }}" class="text-[11px] text-red-600 dark:text-red-500 hover:underline font-bold block mt-3 uppercase tracking-wider">Periksa Bukti &rarr;</a>
                </div>

                {{-- CARD 3 --}}
                <div class="p-5 bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm transition duration-200 hover:border-red-500 dark:hover:border-red-600/50 group backdrop-blur-sm">
                    <div class="flex justify-between items-center">
                        <p class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider">Total Kas Masuk</p>
                        <div class="p-2 bg-green-50 dark:bg-green-600/10 text-green-600 dark:text-green-500 rounded-xl group-hover:bg-green-600 group-hover:text-white transition duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H4zm2 3a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2H6zm8 3a1 1 0 1 0 0-2h.01a1 1 0 1 0 0 2H14z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                    <p class="text-xl font-black mt-3 text-gray-900 dark:text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <span class="text-[10px] text-gray-500 dark:text-gray-400 block mt-3.5 font-medium uppercase tracking-wider">Akumulasi Pembayaran Valid</span>
                </div>

                {{-- CARD 4 --}}
                <div class="p-5 bg-white dark:bg-gray-900/50 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm transition duration-200 hover:border-red-500 dark:hover:border-red-600/50 group backdrop-blur-sm">
                    <div class="flex justify-between items-center">
                        <p class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider">Ulasan Baru</p>
                        <div class="p-2 bg-blue-50 dark:bg-blue-600/10 text-blue-600 dark:text-blue-500 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M18 5v8a2 2 0 0 1-2 2h-5l-5 4v-4H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"/></svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black mt-2 text-gray-900 dark:text-white">{{ $pendingReviews }} <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">Antrean</span></p>
                    <a href="{{ route('admin.reviews.index') }}" class="text-[11px] text-red-600 dark:text-red-500 hover:underline font-bold block mt-3 uppercase tracking-wider">Kelola ulasan &rarr;</a>
                </div>
            </div>

            {{-- SECTION GRAFIK ANALISA & PANEL TA DYNAMIC --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- BOX CANVAS GRAFIK --}}
                <div class="lg:col-span-2 p-6 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm dark:shadow-xl">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white tracking-wide">Tren Kehadiran Latihan</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total member masuk (Check-In) per hari di lokasi gym.</p>
                        </div>
                        <span class="text-xs px-2.5 py-1 bg-red-50 dark:bg-red-600/10 border border-red-200 dark:border-red-900 text-red-600 dark:text-red-500 rounded-lg font-bold">Live Data</span>
                    </div>
                    <div class="w-full h-72 relative">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>

                {{-- BOX LIVE CHECK-IN --}}
                <div class="p-6 bg-white dark:bg-gray-950 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm dark:shadow-xl flex flex-col h-[400px]">
                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-900 pb-3 mb-4">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-wide flex items-center gap-2">
                            <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></span> Check-In Hari Ini
                        </h3>
                        <span class="text-[10px] font-bold text-gray-400">{{ $todayPresences->count() }} Orang</span>
                    </div>

                    {{-- Daftar Member --}}
                    <div class="overflow-y-auto custom-scrollbar flex-1 space-y-3">
                        @forelse($todayPresences as $presence)
                            <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-sm border border-indigo-200 dark:border-indigo-800 overflow-hidden shadow-sm">
                                @if($presence->user->member && !empty($presence->user->member->profile_picture))
                                    {{-- Mengambil dari relasi user -> member -> profile_picture --}}
                                    <img src="{{ asset($presence->user->member->profile_picture) }}" 
                                        alt="{{ $presence->user->name }}" 
                                        class="w-full h-full object-cover">
                                @else
                                    {{-- Jika tidak ada foto, tampilkan Inisial dengan background gradient --}}
                                    <div class="w-full h-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                        {{ substr($presence->user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>  
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $presence->user->name }}</p>
                                    <p class="text-[10px] text-gray-500 font-mono">
                                        {{ \Carbon\Carbon::parse($presence->check_in)->format('H:i') }} WIB
                                    </p>
                                </div>
                                <div class="w-2 h-2 rounded-full bg-green-400"></div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-gray-400 dark:text-gray-600">
                                <svg class="w-10 h-10 mb-2 opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/></svg>
                                <p class="text-xs font-bold uppercase">Belum ada yang absen</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPT RENDERING GRAFIK DINAMIS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const canvasEl = document.getElementById('attendanceChart');
            if(!canvasEl) return;

            const ctx = canvasEl.getContext('2d');
            const redGradient = ctx.createLinearGradient(0, 0, 0, 300);
            redGradient.addColorStop(0, 'rgba(239, 68, 68, 0.4)'); 
            redGradient.addColorStop(1, 'rgba(3, 7, 18, 0.0)');

            // Deteksi state awal tema saat chart dirender
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? '#1f2937' : '#e5e7eb';
            const textColor = isDark ? '#9ca3af' : '#4b5563';

            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Member Kunjungan',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#dc2626', 
                        borderWidth: 3,
                        backgroundColor: redGradient,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#dc2626',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            min: 0,
                            suggestedMax: 5,
                            grid: { color: gridColor, drawBorder: false },
                            ticks: { color: textColor, font: { weight: 'bold', size: 10 }, stepSize: 1 }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { weight: 'bold', size: 10 } }
                        }
                    }
                }
            });

            // Sinkronisasi khusus agar garis pembantu grafik ikut mendeteksi klik tombol secara realtime
            window.renderChart = function() {
                const currentDark = document.documentElement.classList.contains('dark');
                myChart.options.scales.y.grid.color = currentDark ? '#1f2937' : '#e5e7eb';
                myChart.options.scales.y.ticks.color = currentDark ? '#9ca3af' : '#4b5563';
                myChart.options.scales.x.ticks.color = currentDark ? '#9ca3af' : '#4b5563';
                myChart.update();
            }
        });

        setInterval(function(){
        window.location.reload();
        }, 30000);
    </script>
</x-app-layout>