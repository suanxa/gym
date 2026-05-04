<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Finalisasi Membership') }}
        </h2>
    </x-slot>

    @php
        $lastPayment = $member->payments()->latest()->first();
        $isRejected = ($lastPayment && $lastPayment->status == 'rejected');
        
        $isNewUser = !$member->phone_number;
        $isFirstTime = $isNewUser || ($isRejected && $member->status == 'inactive');
    @endphp

    <div class="py-12" x-data="{ 
        memberType: '{{ $member->type ?? 'umum' }}',
        duration: 1, 
        paymentMethod: 'transfer',
        prices: {{ $priceSettings->toJson() }},
        
        get registrationFee() {
            return {{ $isFirstTime ? 'this.prices[this.memberType].registration_fee' : 0 }};
        },
        get pricePerMonth() {
            return this.prices[this.memberType].price;
        },
        {{-- Total Harga Langganan saja --}}
        get subtotalDuration() {
            return this.pricePerMonth * this.duration;
        },
        {{-- Total Akhir --}}
        get totalPrice() {
            return this.subtotalDuration + parseFloat(this.registrationFee);
        },
        formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
        }
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- NOTIFIKASI JIKA PEMBAYARAN DITOLAK --}}
            @if($isRejected)
                <div class="mb-6 p-5 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-4 shadow-sm animate-pulse">
                    <div class="p-2 bg-red-100 rounded-lg text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-red-800 font-black uppercase text-xs tracking-widest">
                            {{ $isFirstTime ? 'Pendaftaran Ditolak!' : 'Perpanjangan Ditolak!' }}
                        </h3>
                        <p class="text-sm text-red-700 mt-1 leading-relaxed">
                            Mohon maaf, bukti pembayaran Anda **ditolak oleh Admin**. 
                            @if(!$isFirstTime)
                                Masa aktif Anda tidak berubah. Silakan kirim ulang bukti transfer perpanjangan yang benar.
                            @else
                                Silakan perbaiki data profil dan kirim kembali bukti transfer pendaftaran Anda.
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-8 border border-gray-100 text-left">
                <div class="mb-8 border-b pb-4">
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ $isFirstTime ? 'Finalisasi Membership 🏋️‍♂️' : 'Perpanjang Membership 🔄' }}
                    </h2>
                    <p class="text-gray-600 text-sm italic">
                        {{ $isFirstTime ? 'Lengkapi formulir di bawah ini untuk pendaftaran member baru.' : 'Masa aktif Anda tetap aman. Silakan unggah ulang bukti transfer perpanjangan.' }}
                    </p>
                </div>

                <form action="{{ route('member.payments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    {{-- FORM PROFIL (Hanya jika pendaftaran awal) --}}
                    @if($isFirstTime)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700 uppercase">Nomor Telepon/WA</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $member->phone_number) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-3 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700 uppercase">Tanggal Lahir</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $member->date_of_birth) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-3 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700 uppercase">Jenis Kelamin</label>
                                <select name="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-3 focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="L" {{ old('gender', $member->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender', $member->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-bold text-gray-700 uppercase">Kategori Member</label>
                                <select name="type" x-model="memberType" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-3 focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="umum">Umum</option>
                                    <option value="pelajar">Pelajar</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700 uppercase">Alamat Lengkap</label>
                            <textarea name="address" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-3 focus:ring-blue-500 focus:border-blue-500" required>{{ old('address', $member->address) }}</textarea>
                        </div>
                        <div x-show="memberType === 'pelajar'" x-transition class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                            <label class="block mb-2 text-sm font-bold text-yellow-800 text-left">Upload Kartu Pelajar</label>
                            <input type="file" name="student_card" :required="memberType === 'pelajar'" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white">
                        </div>
                    @else
                        <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-2xl flex justify-between items-center shadow-sm">
                            <div class="text-left">
                                <p class="text-[10px] text-indigo-500 font-black uppercase tracking-widest">Member Terverifikasi</p>
                                <p class="text-lg font-black text-indigo-900 uppercase tracking-tight">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-indigo-700 font-bold uppercase">Tipe: <span x-text="memberType"></span></p>
                            </div>
                            <input type="hidden" name="type" :value="memberType">
                        </div>
                    @endif

                    <div class="mt-8">
                        <label class="block mb-4 text-sm font-bold text-gray-700 uppercase tracking-tight text-left">Pilih Durasi Membership</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <template x-for="m in [1, 3, 6, 12]">
                                <label class="relative flex flex-col p-4 bg-white border border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all shadow-sm" :class="duration == m ? 'border-blue-600 ring-2 ring-blue-50' : ''">
                                    <input type="radio" name="duration" :value="m" x-model="duration" class="absolute top-4 right-4 text-blue-600 focus:ring-blue-500">
                                    <span class="text-xl font-black text-gray-900 text-left" x-text="m + ' Bln'"></span>
                                    <span class="text-[9px] text-gray-500 uppercase font-black tracking-widest mt-1 text-left" x-text="m >= 3 ? 'Paket Hemat' : 'Normal'"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div class="mt-8 border-t pt-8 text-left">
                        <label class="block mb-4 text-sm font-bold text-gray-700 uppercase tracking-tight">Pilih Metode Pembayaran</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 bg-white border border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all shadow-sm" :class="paymentMethod === 'transfer' ? 'border-blue-600 ring-2 ring-blue-50' : ''">
                                <input type="radio" x-model="paymentMethod" value="transfer" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-4">
                                    <span class="block text-sm font-bold text-gray-900 uppercase">Transfer Bank</span>
                                    <span class="text-[10px] text-gray-500 font-medium tracking-tighter">BANK XYZ (MANUAL)</span>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 bg-white border border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all shadow-sm" :class="paymentMethod === 'qris' ? 'border-blue-600 ring-2 ring-blue-50' : ''">
                                <input type="radio" x-model="paymentMethod" value="qris" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                <div class="ml-4">
                                    <span class="block text-sm font-bold text-gray-900 uppercase">QRIS / E-WALLET</span>
                                    <span class="text-[10px] text-gray-500 font-medium tracking-tighter">SCAN & BAYAR</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- RINGKASAN TAGIHAN TERPERINCI --}}
                    <div class="border-t pt-8">
                        <div class="bg-gray-900 rounded-3xl p-8 text-white shadow-2xl space-y-5">
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-500 border-b border-gray-800 pb-3">Rincian Tagihan</h3>
                            
                            <div class="space-y-3">
                                {{-- Harga Langganan --}}
                                <div class="flex justify-between text-sm">
                                    <div class="flex flex-col">
                                        <span class="text-gray-300 font-medium">Biaya Membership</span>
                                        <span class="text-[10px] text-gray-500 uppercase italic">
                                            <span x-text="formatRupiah(pricePerMonth)"></span> x <span x-text="duration"></span> Bulan
                                        </span>
                                    </div>
                                    <span class="font-bold text-gray-100" x-text="formatRupiah(subtotalDuration)"></span>
                                </div>
                                
                                {{-- Biaya Pendaftaran --}}
                                <template x-if="registrationFee > 0">
                                    <div class="flex justify-between text-sm items-center">
                                        <span class="text-blue-400 font-medium">Biaya Registrasi Awal</span>
                                        <span class="font-bold text-blue-400" x-text="formatRupiah(registrationFee)"></span>
                                    </div>
                                </template>
                            </div>

                            <div class="border-t border-gray-800 pt-5 flex justify-between items-center">
                                <div class="flex flex-col">
                                    <span class="text-xs font-black uppercase tracking-widest text-gray-400">Total Pembayaran</span>
                                    <span class="text-[9px] text-blue-500 font-bold uppercase mt-1">Sudah termasuk pajak & admin</span>
                                </div>
                                <span class="text-4xl font-black text-white tracking-tighter" x-text="formatRupiah(totalPrice)"></span>
                            </div>
                        </div>

                        <input type="hidden" name="amount" :value="totalPrice">

                        <div class="mt-8 p-6 bg-blue-50 rounded-3xl border border-blue-100">
                            <div x-show="paymentMethod === 'transfer'" x-transition class="text-left">
                                <p class="text-sm font-bold text-blue-900 mb-2 uppercase">Instruksi Transfer Bank:</p>
                                <div class="space-y-1">
                                    <p class="text-xs text-blue-800">Bank: <b>Bank XYZ</b></p>
                                    <p class="text-xs text-blue-800">No. Rekening: <b>123-456-7890</b></p>
                                    <p class="text-xs text-blue-800">Atas Nama: <b>Piai Futsal Fitness</b></p>
                                </div>
                            </div>
                            <div x-show="paymentMethod === 'qris'" x-transition class="text-center flex flex-col items-center">
                                <p class="text-sm font-bold text-blue-900 mb-4 uppercase">Scan QRIS Untuk Membayar:</p>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=PIAI_FITNESS_QRIS" class="w-48 h-48 bg-white p-2 rounded-2xl shadow-md border-4 border-white mb-4">
                                <p class="text-[10px] text-blue-700 font-bold uppercase italic tracking-widest">Bisa menggunakan Dana, OVO, GoPay, atau M-Banking</p>
                            </div>
                        </div>

                        <div class="mt-8 space-y-3 text-left">
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-tight">Upload Bukti Bayar</label>
                            <input type="file" name="proof_of_payment" class="block w-full text-sm text-gray-500 border border-gray-300 rounded-2xl cursor-pointer bg-white focus:outline-none p-3 font-bold" required>
                        </div>
                    </div>

                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 font-black rounded-3xl text-lg px-5 py-5 text-center shadow-xl transition-all transform hover:-translate-y-1 active:scale-95 uppercase tracking-widest">
                        {{ $isFirstTime ? 'Konfirmasi Pendaftaran' : 'Konfirmasi Perpanjangan' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>