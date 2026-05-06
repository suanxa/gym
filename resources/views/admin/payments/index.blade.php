<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaksi Pembayaran & Kasir') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showForm: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 font-bold">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="mb-6 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-700">Riwayat Transaksi</h3>
                <button @click="showForm = !showForm" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-lg shadow-blue-200">
                    <span x-text="showForm ? '- Tutup Form' : '+ Input Transaksi Luar (Guest)'"></span>
                </button>
            </div>

            <div x-show="showForm" x-transition class="bg-white p-6 rounded-xl shadow-md border border-blue-100 mb-8">
                <h4 class="font-bold text-gray-800 mb-4 text-blue-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Form Transaksi Guest (Non-Member)
                </h4>
                <form action="{{ route('admin.payments.store_manual') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Nama Pelanggan</label>
                            <input type="text" name="external_customer_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-blue-500 focus:border-blue-500" placeholder="Nama pengunjung..." required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Jumlah Bayar (Rp)</label>
                            <input type="number" name="amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 focus:ring-blue-500 focus:border-blue-500" placeholder="50000" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Keterangan Layanan</label>
                            <select name="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5" required>
                                <option value="Sewa Lapangan">Sewa Lapangan (Futsal)</option>
                                <option value="Sewa Alat Fitness">Sewa Alat Fitness (Insidental)</option>
                                <option value="Minuman/Snack">Kantin / Minuman</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <button type="button" @click="showForm = false" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg font-bold">Batal</button>
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg font-bold shadow-md hover:bg-green-700 transition">Simpan Transaksi</button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Tanggal</th>
                                <th class="px-6 py-3">Nama Pelanggan</th>
                                <th class="px-6 py-3 text-center">Jenis Pelanggan</th> 
                                <th class="px-6 py-3">Kategori Layanan</th>
                                <th class="px-6 py-3">Nominal</th>
                                <th class="px-6 py-3">Bukti</th> <!-- Tambah kolom ini -->
                                <th class="px-6 py-3 text-center">Status & Aksi</th> <!-- Edit kolom ini -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr class="bg-white border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-xs text-gray-400">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ $payment->user->name ?? $payment->external_customer_name }}
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    @if($payment->user_id)
                                        <span class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-wider {{ $payment->user->member?->type == 'pelajar' ? 'bg-orange-100 text-orange-700 border border-orange-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                            Member {{ $payment->user->member?->type ?? 'Umum' }}
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-300">
                                            Pelanggan Biasa
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 italic text-xs text-gray-600">{{ $payment->description ?? 'Iuran Membership' }}</td>
                                <td class="px-6 py-4 text-gray-900 font-black">Rp {{ number_format($payment->amount) }}</td>

                                <!-- BAGIAN PERBAIKAN GAMBAR -->
                                <td class="px-6 py-4">
                                    @if($payment->proof_of_payment)
                                        {{-- Menggunakan asset() agar langsung ke folder public/uploads tanpa lewat folder storage --}}
                                        <a href="{{ asset($payment->proof_of_payment) }}" target="_blank">
                                            <img src="{{ asset($payment->proof_of_payment) }}" 
                                                class="w-12 h-12 object-cover rounded shadow-sm border border-gray-200 hover:scale-125 transition" 
                                                alt="Bukti">
                                        </a>
                                    @else
                                        <span class="text-gray-300 italic text-[10px]">Cash/Manual</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col gap-2 items-center">
                                        <span class="px-2 py-1 rounded text-[10px] font-bold {{ $payment->status == 'verified' ? 'bg-green-500 text-white' : 'bg-yellow-400 text-white' }}">
                                            {{ strtoupper($payment->status) }}
                                        </span>

                                        {{-- Tombol Verifikasi hanya muncul jika status masih pending --}}
                                        @if($payment->status == 'pending')
                                            <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-[9px] bg-blue-600 hover:bg-blue-700 text-white py-1 px-2 rounded font-bold transition">
                                                    Verifikasi Sekarang
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center italic text-gray-400">Belum ada transaksi pembayaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>