<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PIAI FITNESS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0f0d0d] font-sans">

    <div class="min-h-screen flex items-center justify-center p-4">
        {{-- Card Utama --}}
        <div class="max-w-4xl w-full flex bg-[#161414] rounded-3xl overflow-hidden shadow-2xl border border-[#222]">
            
            {{-- Sisi Kiri: Visual (Sembunyikan di Mobile) --}}
            <div class="hidden lg:block w-1/2 relative">
                <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1000" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-red-600/60 mix-blend-multiply"></div>
                <div class="absolute inset-0 flex flex-col justify-center p-12 text-white">
                    <h2 class="text-4xl font-black uppercase tracking-tighter leading-tight mb-4">SIAP UNTUK <br> <span class="text-white">LEVEL BARU?</span></h2>
                    <p class="text-red-100 font-medium opacity-80">Masukkan kredensialmu untuk mengakses dasbor admin.</p>
                </div>
            </div>

            {{-- Sisi Kanan: Form --}}
            <div class="w-full lg:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                <h2 class="text-2xl font-black text-white uppercase tracking-tighter mb-6">Selamat Datang</h2>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-gray-400 text-xs font-bold uppercase">Email</label>
                        <input type="email" name="email" class="w-full mt-1 p-3 bg-[#0f0d0d] border border-[#333] text-white rounded-lg focus:border-red-600 outline-none" required>
                    </div>
                    <div>
                        <label class="text-gray-400 text-xs font-bold uppercase">Password</label>
                        <input type="password" name="password" class="w-full mt-1 p-3 bg-[#0f0d0d] border border-[#333] text-white rounded-lg focus:border-red-600 outline-none" required>
                    </div>

                    <div class="flex items-center justify-between text-xs font-bold uppercase text-gray-500">
                        <label class="flex items-center"><input type="checkbox" name="remember" class="mr-2"> Ingat Saya</label>
                        <a href="{{ route('password.request') }}" class="text-red-500">Lupa Password?</a>
                    </div>

                    <button type="submit" class="w-full py-4 bg-red-600 text-white font-black uppercase tracking-widest text-sm hover:bg-red-700 transition">Log in</button>
                </form>

                <div class="my-6 text-center text-[10px] text-gray-600 uppercase font-bold">Atau</div>

                <a href="{{ route('google.login') }}" class="w-full py-4 border border-[#333] text-white text-center font-black uppercase tracking-widest text-sm hover:bg-white hover:text-black transition">
                    Login dengan Google
                </a>
            </div>
        </div>
    </div>

</body>
</html>