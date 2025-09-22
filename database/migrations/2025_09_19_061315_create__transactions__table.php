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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->comment('ID Resepsionis yang memproses');
            $table->dateTime('transaction_date');
            $table->decimal('amount', 10, 2);
            // PERBAIKAN: Memastikan kolom enum didefinisikan dengan benar saat tabel dibuat
            $table->enum('payment_method', ['cash', 'debit', 'qris'])->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

