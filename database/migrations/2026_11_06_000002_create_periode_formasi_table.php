<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_formasi', function (Blueprint $table) {
            $table->foreignId('periode_id')->constrained('periode_lokeres')->cascadeOnDelete();
            $table->foreignId('formasi_id')->constrained('formasis')->cascadeOnDelete();
            $table->primary(['periode_id', 'formasi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_formasi');
    }
};
