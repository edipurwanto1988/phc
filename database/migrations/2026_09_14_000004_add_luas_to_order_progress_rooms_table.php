<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_progress_rooms', function (Blueprint $table) {
            $table->decimal('luas', 10, 2)->nullable()->after('ruangan'); // luas ruangan dalam m²
        });
    }

    public function down(): void
    {
        Schema::table('order_progress_rooms', function (Blueprint $table) {
            $table->dropColumn('luas');
        });
    }
};
