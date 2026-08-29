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
            $table->string('foto_sebelum')->nullable()->after('status');
            $table->string('foto_sesudah')->nullable()->after('foto_sebelum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_assignments', function (Blueprint $table) {
            $table->dropColumn(['foto_sebelum', 'foto_sesudah']);
        });
    }
};
