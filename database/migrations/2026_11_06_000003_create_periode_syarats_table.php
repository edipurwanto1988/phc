<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_syarats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periode_id')->constrained('periode_lokeres')->cascadeOnDelete();
            $table->string('nama');
            $table->boolean('wajib')->default(false);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_syarats');
    }
};
