<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
             // Informasi utama website / mitra
            $table->string('site_name'); // Nama website / usaha
            $table->text('description')->nullable(); // Deskripsi singkat

            // Kontak
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();

            // Media
            $table->string('logo')->nullable();   // path logo
            $table->string('banner')->nullable(); // gambar banner

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
