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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kategori_biaya'); // Contoh: Transportasi, Alat Kebersihan, Gaji, dll.
            $table->decimal('jumlah', 12, 2);
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // pelaksana (User/Admin/Staff)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
