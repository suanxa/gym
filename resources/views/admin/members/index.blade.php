<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Member Piai Futsal Fitness') }}
        </h2>
    </x-slot>

    <div class="py-6" x-data="{ selectedMember: null, showModal: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 font-bold">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 font-bold">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3">Tipe</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Masa Berlaku</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $member)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $member->user->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded text-xs font-bold {{ $member->type == 'pelajar' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                                        {{ strtoupper($member->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold {{ $member->status == 'active' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ strtoupper($member->status) }}
                                        </span>
                                        
                                        {{-- Indikator Pintar: Membedakan Member Baru vs Perpanjangan --}}
                                        @if($member->payments->first() && $member->payments->first()->status == 'pending')
                                            @if($member->status == 'active')
                                                <span class="text-[10px] bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-black animate-pulse mt-1 w-fit">
                                                    ADA PERPANJANGAN
                                                </span>
                                            @else
                                                <span class="text-[10px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-black animate-pulse mt-1 w-fit">
                                                    AKTIVASI BARU
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs">
                                    {{ $member->membership_expiry ? \Carbon\Carbon::parse($member->membership_expiry)->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{-- Mengirim data member lengkap dengan relasi payments terakhir ke Alpine --}}
                                    <button 
                                        @click="selectedMember = {{ $member->toJson() }}; showModal = true"
                                        class="text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1 rounded text-xs font-bold transition shadow-sm uppercase">
                                        Detail & Konfirmasi
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center italic text-gray-400">Tidak ada data member.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL & KONFIRMASI --}}
        <div x-show="showModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-cloak>
            
            <div class="flex items-center justify-center min-h-screen p-4 text-center">
                <div class="fixed inset-0 bg-black bg-opacity-60 transition-opacity" @click="showModal = false"></div>

                <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-2xl sm:w-full p-6"
                     @click.away="showModal = false">
                    
                    <div class="flex justify-between items-center border-b pb-3 mb-5">
                        <h3 class="text-xl font-bold text-gray-900">Verifikasi Membership</h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
                    </div>

                    <template x-if="selectedMember">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Info Data Diri --}}
                            <div class="space-y-4 text-left">
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Nama Lengkap</p>
                                    <p class="font-bold text-gray-800 text-lg uppercase" x-text="selectedMember.user.name"></p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">No. WA</p>
                                        <p class="text-sm font-semibold" x-text="selectedMember.phone_number || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Gender</p>
                                        <p class="text-sm font-semibold" x-text="selectedMember.gender == 'L' ? 'Laki-laki' : 'Perempuan'"></p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Alamat</p>
                                    <p class="text-sm text-gray-600 leading-relaxed" x-text="selectedMember.address || '-'"></p>
                                </div>
                                <template x-if="selectedMember.membership_expiry">
                                    <div>
                                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Masa Aktif Saat Ini</p>
                                        <p class="text-sm font-bold text-blue-600" x-text="selectedMember.membership_expiry"></p>
                                    </div>
                                </template>
                            </div>

                            {{-- Preview Gambar --}}
                            <div class="space-y-4">
                                {{-- Kartu Pelajar --}}
                                <div x-show="selectedMember.type == 'pelajar'">
                                    <p class="text-[10px] text-orange-600 uppercase font-black mb-1">Kartu Pelajar</p>
                                    <template x-if="selectedMember.student_card">
                                        <a :href="`{{ asset('') }}${selectedMember.student_card}`" target="_blank">
                                                <img :src="`{{ asset('') }}${selectedMember.student_card}`" 
                                                 class="w-full h-32 object-cover rounded-xl border-2 border-orange-100 hover:border-orange-400 transition">
                                        </a>
                                    </template>
                                </div>
                                
                                {{-- Bukti Transfer --}}
                                <div>
                                    <p class="text-[10px] text-blue-600 uppercase font-black mb-1">Bukti Transfer Terbaru</p>
                                    <template x-if="selectedMember.payments && selectedMember.payments.length > 0">
                                        <a :href="`{{ asset('') }}${selectedMember.payments[0].proof_of_payment}`" target="_blank">
                                                <img :src="`{{ asset('') }}${selectedMember.payments[0].proof_of_payment}`"
                                                 class="w-full h-40 object-cover rounded-xl border-2 border-blue-100 hover:border-blue-400 transition">
                                        </a>
                                    </template>
                                    <template x-if="!selectedMember.payments || selectedMember.payments.length == 0">
                                        <div class="h-32 bg-gray-50 border-2 border-dashed rounded-xl flex items-center justify-center">
                                            <span class="text-[10px] text-gray-400 uppercase font-bold text-center">Belum ada bukti<br>pembayaran</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Tombol Aksi (Muncul Jika Pembayaran Terakhir Statusnya PENDING) --}}
                    <div class="mt-8 flex flex-wrap gap-3 border-t pt-5">
                        <template x-if="selectedMember && selectedMember.payments.length > 0 && selectedMember.payments[0].status == 'pending'">
                            <div class="flex flex-1 gap-3">
                                {{-- Tombol Setujui --}}
                                <form :action="`{{ url('/admin/members/approve') }}/${selectedMember.id}`" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 text-white font-black py-3 rounded-xl hover:bg-green-700 shadow-lg transition transform hover:-translate-y-0.5 uppercase tracking-tighter">
                                        Konfirmasi Bayar (<span x-text="selectedMember.payments[0].duration"></span> Bln)
                                    </button>
                                </form>

                                {{-- Tombol Tolak --}}
                                <form :action="`{{ url('/admin/members/reject') }}/${selectedMember.id}`" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin menolak pembayaran ini?')">
                                    @csrf
                                    <button type="submit" class="w-full bg-red-600 text-white font-black py-3 rounded-xl hover:bg-red-700 shadow-lg transition transform hover:-translate-y-0.5 uppercase tracking-tighter">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </template>

                        {{-- Tombol jika tidak ada antrian pembayaran --}}
                        <template x-if="selectedMember && (selectedMember.payments.length == 0 || selectedMember.payments[0].status != 'pending')">
                            <div class="flex-1 text-center py-3 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <span class="text-xs text-gray-500 font-bold uppercase">Status Pembayaran: <span class="text-indigo-600" x-text="selectedMember.payments.length > 0 ? selectedMember.payments[0].status : 'N/A'"></span></span>
                            </div>
                        </template>
                        
                        <button @click="showModal = false" class="px-6 bg-gray-100 text-gray-500 font-bold py-3 rounded-xl hover:bg-gray-200 transition uppercase text-xs">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>