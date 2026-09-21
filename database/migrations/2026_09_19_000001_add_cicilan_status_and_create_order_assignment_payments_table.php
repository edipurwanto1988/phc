<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'cicilan' (partial payment) status to the salary status enum
        DB::statement("ALTER TABLE order_assignments MODIFY status_gaji ENUM('belum_dibayar','cicilan','sudah_dibayar') NOT NULL DEFAULT 'belum_dibayar'");

        // Payment history for salary installments (cash bon / pelunasan)
        Schema::create('order_assignment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('order_assignments')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('type', ['cashbon', 'pelunasan'])->default('cashbon');
            $table->date('payment_date');
            $table->text('catatan')->nullable();
            $table->foreignId('expense_id')->nullable()->constrained('expenses')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_assignment_payments');

        DB::statement("ALTER TABLE order_assignments MODIFY status_gaji ENUM('belum_dibayar','sudah_dibayar') NOT NULL DEFAULT 'belum_dibayar'");
    }
};
