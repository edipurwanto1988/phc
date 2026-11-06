<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loker_berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loker_id')->constrained('lokeres')->cascadeOnDelete();
            $table->foreignId('periode_syarat_id')->nullable()->constrained('periode_syarats')->nullOnDelete();
            $table->string('path');
            $table->timestamps();

            $table->unique(['loker_id', 'periode_syarat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loker_berkas');
    }
};
