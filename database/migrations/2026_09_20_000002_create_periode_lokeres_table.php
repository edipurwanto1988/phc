<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_lokeres', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->date('tanggal')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('nonaktif');
            $table->timestamps();
        });

        Schema::table('lokeres', function (Blueprint $table) {
            $table->foreignId('periode_id')->nullable()->after('id')->constrained('periode_lokeres')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lokeres', function (Blueprint $table) {
            $table->dropConstrainedForeignId('periode_id');
        });
        Schema::dropIfExists('periode_lokeres');
    }
};
