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
        Schema::create('payments', function (Blueprint $table) {
        $table->id();
        
        // Relasi ke User (untuk login member)
        $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
        
        // Relasi ke Member (untuk update masa aktif)
        $table->foreignId('member_id')->nullable()->constrained()->onDelete('cascade');

        // Untuk tamu yang tidak punya akun
        $table->string('external_customer_name')->nullable(); 
        
        $table->integer('amount');
        $table->integer('duration')->default(1); // Jumlah bulan (1, 3, 6, 12)
        
        $table->date('payment_date');
        $table->string('proof_of_payment')->nullable();
        $table->string('description')->nullable();
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
