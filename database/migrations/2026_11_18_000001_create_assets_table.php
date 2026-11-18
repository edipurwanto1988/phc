<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_alat')->nullable()->unique();
            $table->string('nama');
            $table->string('kategori')->nullable();
            $table->string('merk')->nullable();
            $table->date('tanggal_beli')->nullable();
            $table->decimal('harga', 12, 2)->default(0); // nilai perolehan
            $table->foreignId('expense_id')->nullable()->constrained('expenses')->nullOnDelete(); // pengeluaran pembelian
            $table->string('kondisi')->default('baik'); // baik, rusak, dalam_perbaikan, tidak_aktif
            $table->string('lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
