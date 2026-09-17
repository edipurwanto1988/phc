<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Progress pekerjaan per lantai & ruangan untuk dibagikan ke client
        Schema::create('order_progress_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('lantai')->nullable();          // contoh: Lantai 1, Lantai 2
            $table->string('ruangan');                     // contoh: Kamar Tidur Utama, Dapur
            $table->enum('status', ['belum', 'selesai'])->default('belum');
            $table->text('catatan')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Token publik unik untuk membagikan link progress ke client
        Schema::table('orders', function (Blueprint $table) {
            $table->string('progress_token', 64)->nullable()->unique()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('progress_token');
        });

        Schema::dropIfExists('order_progress_rooms');
    }
};
