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
    Schema::create('members', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('phone_number')->nullable(); // Tambahkan nullable
        $table->text('address')->nullable();      // Tambahkan nullable
        $table->date('date_of_birth')->nullable(); // Tambahkan nullable
        $table->enum('gender', ['L', 'P'])->nullable(); // Tambahkan nullable
        $table->string('student_card')->nullable(); // Tambahkan nullable
        $table->enum('type', ['umum', 'pelajar'])->default('umum');
        $table->enum('status', ['inactive', 'pending', 'active'])->default('inactive');
        $table->date('membership_expiry')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
