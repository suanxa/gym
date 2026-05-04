<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Selamat datang, {{ auth()->user()->name }}!</h1>
                    <p class="mb-4">Ini adalah halaman kontrol utama untuk Piai Futsal Fitness.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="p-4 bg-blue-100 rounded-lg shadow-sm border border-blue-200">
                            <p class="text-sm text-blue-600 font-bold uppercase">Total Member</p>
                            <p class="text-2xl font-bold">Lacak di Menu Member</p>
                        </div>
                        <div class="p-4 bg-green-100 rounded-lg shadow-sm border border-green-200">
                            <p class="text-sm text-green-600 font-bold uppercase">Pembayaran Pending</p>
                            <p class="text-2xl font-bold">Cek Konfirmasi</p>
                        </div>
                        <div class="p-4 bg-yellow-100 rounded-lg shadow-sm border border-yellow-200">
                            <p class="text-sm text-yellow-600 font-bold uppercase">Status Sistem</p>
                            <p class="text-2xl font-bold italic">Online</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>