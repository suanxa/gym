<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('prices')->insert([
            [
                'category' => 'umum',
                'price' => 175000,
                'registration_fee' => 10000,
                'description' => 'Harga membership kategori umum per bulan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'pelajar',
                'price' => 150000,
                'registration_fee' => 10000,
                'description' => 'Harga membership khusus pelajar/mahasiswa per bulan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'guest',
                'price' => 25000, // Misal harga per kedatangan untuk non-member
                'registration_fee' => 0,
                'description' => 'Harga kunjungan harian non-member (Guest)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}