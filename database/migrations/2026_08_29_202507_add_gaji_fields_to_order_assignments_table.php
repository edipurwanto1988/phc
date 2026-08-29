<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_assignments', function (Blueprint $table) {
            $table->decimal('gaji', 12, 2)->default(0.00)->after('finished_at');
            $table->enum('status_gaji', ['belum_dibayar', 'sudah_dibayar'])->default('belum_dibayar')->after('gaji');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_assignments', function (Blueprint $table) {
            $table->dropColumn(['gaji', 'status_gaji']);
        });
    }
};
