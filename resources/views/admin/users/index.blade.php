<x-app-layout>
    <style>
        .dark body { background-color: #030712 !important; }
        body { background-color: #f3f4f6 !important; transition: background-color 0.2s ease; }
    </style>

    <div class="p-4 sm:ml-1 min-h-screen pt-2 text-gray-800 dark:text-gray-100 transition-colors duration-200" x-data="{ showModal: false }">
        <div class="max-w-5xl mx-auto space-y-6">
            
            {{-- HEADER SECTION --}}
            <div class="p-6 bg-gradient-to-r from-white via-slate-50 to-indigo-50 dark:from-gray-950 dark:via-gray-900 dark:to-indigo-950/40 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-black uppercase tracking-tight">Manajemen <span class="text-indigo-600 dark:text-indigo-400">Admin</span></h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Daftar akun akses pengelola sistem PIAI WELLNESS.</p>
                </div>
                <button @click="showModal = true" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-3 px-6 rounded-2xl transition shadow-lg shadow-indigo-600/20 text-xs uppercase tracking-widest">
                    ＋ Tambah Admin Baru
                </button>
            </div>

            @if(session('success'))
                <div class="p-4 text-sm text-green-800 dark:text-green-400 rounded-xl bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900/50 font-bold italic">{{ session('success') }}</div>
            @endif

            {{-- TABEL ADMIN --}}
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl shadow-sm overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] uppercase bg-gray-50 dark:bg-gray-950 text-gray-500 tracking-widest">
                        <tr>
                            <th class="px-6 py-4 text-center">No</th>
                            <th class="px-6 py-4">Nama</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Bergabung</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($admins as $index => $admin)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <td class="px-6 py-4 text-center text-xs font-black text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $admin->name }}</td>
                            <td class="px-6 py-4 font-mono text-gray-600 dark:text-gray-400">{{ $admin->email }}</td>
                            <td class="px-6 py-4 text-xs font-semibold">{{ $admin->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($admin->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Hapus admin ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-[10px] bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-3 py-1 rounded-lg font-black uppercase hover:bg-red-100">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-[10px] text-gray-400 italic">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">Belum ada admin lain.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL TAMBAH ADMIN --}}
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
            <div class="fixed inset-0 bg-gray-950/70 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative bg-white dark:bg-gray-950 rounded-3xl shadow-2xl w-full max-w-sm p-8 border border-gray-200 dark:border-gray-800">
                <h3 class="text-lg font-black uppercase mb-6">Tambah Admin Baru</h3>
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block mb-1 text-[10px] font-black uppercase text-gray-500">Nama</label>
                        <input type="text" name="name" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-800 rounded-xl p-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-[10px] font-black uppercase text-gray-500">Email</label>
                        <input type="email" name="email" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-800 rounded-xl p-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block mb-1 text-[10px] font-black uppercase text-gray-500">Password</label>
                        <input type="password" name="password" class="w-full bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-800 rounded-xl p-3 text-sm" required>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" @click="showModal = false" class="flex-1 bg-gray-100 dark:bg-gray-900 text-gray-500 font-bold py-3 rounded-xl text-xs uppercase">Batal</button>
                        <button type="submit" class="flex-1 bg-indigo-600 text-white font-black py-3 rounded-xl text-xs uppercase">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>