<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah status "proses" (Progress Pengerjaan) selain "belum" dan "selesai"
        DB::statement("ALTER TABLE order_progress_rooms MODIFY COLUMN status ENUM('belum', 'proses', 'selesai') NOT NULL DEFAULT 'belum'");
    }

    public function down(): void
    {
        DB::statement("UPDATE order_progress_rooms SET status = 'belum' WHERE status = 'proses'");
        DB::statement("ALTER TABLE order_progress_rooms MODIFY COLUMN status ENUM('belum', 'selesai') NOT NULL DEFAULT 'belum'");
    }
};
