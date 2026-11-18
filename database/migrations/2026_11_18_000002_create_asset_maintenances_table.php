<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('jenis'); // kerusakan, perbaikan
            $table->text('deskripsi');
            $table->decimal('biaya', 12, 2)->default(0);
            $table->foreignId('expense_id')->nullable()->constrained('expenses')->nullOnDelete(); // pengeluaran biaya perbaikan
            $table->string('kondisi_setelah')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_maintenances');
    }
};
