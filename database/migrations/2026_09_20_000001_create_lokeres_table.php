<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokeres', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->text('alamat');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->default('Laki-laki');
            $table->text('pengalaman')->nullable();
            $table->text('keahlian_khusus')->nullable();
            $table->text('cerita')->nullable();
            $table->string('no_wa', 20);
            $table->string('ktp')->nullable();       // path lokal atau URL Google Drive
            $table->string('ktp_drive_id')->nullable(); // ID file di Google Drive (untuk hapus)
            $table->timestamps();

            $table->index('no_wa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokeres');
    }
};
