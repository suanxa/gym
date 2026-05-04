<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Review & Testimoni') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-2xl bg-green-50 border border-green-200 font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Testimoni Member</h3>
                    <p class="text-sm text-gray-500">Pilih testimoni member yang ingin ditampilkan pada halaman utama website.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($reviews as $review)
                        <div class="flex flex-col bg-gray-50 rounded-3xl p-6 border {{ $review->is_published ? 'border-green-200 ring-2 ring-green-50' : 'border-gray-200' }} transition-all">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold uppercase">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm uppercase">{{ $review->user->name }}</h4>
                                        <p class="text-[10px] text-gray-500 font-medium">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 rounded-full text-[10px] font-black uppercase {{ $review->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                    {{ $review->is_published ? 'Published' : 'Hidden' }}
                                </span>
                            </div>

                            <div class="flex-grow">
                                <p class="text-gray-700 text-sm italic leading-relaxed">
                                    "{{ $review->comment }}"
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-200 flex items-center justify-between">
                                <div class="flex text-yellow-400">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-4 h-4 fill-current {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endfor
                                </div>

                                <form action="{{ route('admin.reviews.publish', $review->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-black uppercase tracking-tighter {{ $review->is_published ? 'text-red-600 hover:text-red-800' : 'text-indigo-600 hover:text-indigo-800' }}">
                                        {{ $review->is_published ? 'Sembunyikan' : 'Tampilkan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center">
                            <p class="text-gray-400 italic">Belum ada review yang masuk dari member.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>