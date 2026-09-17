<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_progress_rooms', function (Blueprint $table) {
            $table->string('bukti')->nullable()->after('catatan');   // foto bukti pengerjaan
            $table->text('masalah')->nullable()->after('bukti');     // keterangan jika ada masalah
        });
    }

    public function down(): void
    {
        Schema::table('order_progress_rooms', function (Blueprint $table) {
            $table->dropColumn(['bukti', 'masalah']);
        });
    }
};
