<x-app-layout>
    {{-- FORCE GLOBAL STYLE UNTUK SINKRONISASI TEMA TERANG/GELAP --}}
    <style>
        .dark body, .dark main, .dark .min-h-screen { 
            background-color: #030712 !important; 
        }
        body, main, .min-h-screen { 
            background-color: #f3f4f6 !important; 
            transition: background-color 0.2s ease;
        }
    </style>

    <div class="p-4 sm:ml-1 bg-gray-100 dark:bg-gray-950 min-h-screen pt-2 text-gray-800 dark:text-gray-100 transition-colors duration-200">
        <div class="max-w-7xl mx-auto space-y-6">
            
            {{-- HEADER BANNER MODUL --}}
            <div class="p-6 bg-gradient-to-r from-white via-slate-50 to-indigo-50 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/40 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
                <h1 class="text-2xl font-black tracking-wide text-gray-900 dark:text-white uppercase">
                    Moderasi <span class="text-indigo-600 dark:text-indigo-400">Testimoni</span>
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola publikasi ulasan member untuk membangun kredibilitas Piai Wellness di mata pengunjung.</p>
            </div>

            @if(session('success'))
                <div class="p-4 text-sm text-green-800 dark:text-green-400 rounded-xl bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900/50 font-bold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- GRID TESTIMONI --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($reviews as $review)
                    <div class="flex flex-col bg-white dark:bg-gray-900/50 rounded-3xl p-6 border {{ $review->is_published ? 'border-green-200 dark:border-green-900/50 ring-2 ring-green-50 dark:ring-green-900/20' : 'border-gray-200 dark:border-gray-800' }} transition-all shadow-sm">
                        
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold uppercase shadow-lg shadow-indigo-600/20">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm uppercase">{{ $review->user->name }}</h4>
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-medium">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 rounded-full text-[9px] font-black uppercase tracking-wider {{ $review->is_published ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : 'bg-gray-200 dark:bg-gray-800 text-gray-600 dark:text-gray-400' }}">
                                {{ $review->is_published ? 'Published' : 'Hidden' }}
                            </span>
                        </div>

                        <div class="flex-grow">
                            <p class="text-gray-700 dark:text-gray-300 text-sm italic leading-relaxed">
                                "{{ $review->comment }}"
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div class="flex text-yellow-400">
                                @for($i=1; $i<=5; $i++)
                                    <svg class="w-4 h-4 fill-current {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-700' }}" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endfor
                            </div>

                            <form action="{{ route('admin.reviews.publish', $review->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-[10px] font-black uppercase tracking-widest transition {{ $review->is_published ? 'text-red-600 dark:text-red-400 hover:text-red-800' : 'text-indigo-600 dark:text-indigo-400 hover:text-indigo-800' }}">
                                    {{ $review->is_published ? 'Sembunyikan' : 'Tampilkan' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-400 dark:text-gray-600 font-bold italic">
                        Belum ada review yang masuk dari member.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>