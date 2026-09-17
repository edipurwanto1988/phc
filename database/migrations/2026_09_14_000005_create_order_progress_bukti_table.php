<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Bukti foto pekerjaan per ruangan (multiple)
        Schema::create('order_progress_bukti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_progress_room_id')->constrained('order_progress_rooms')->cascadeOnDelete();
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_progress_bukti');
    }
};
