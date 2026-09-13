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
        Schema::table('orders', function (Blueprint $table) {
            // Already renamed down_payment_due_date to down_payment_date
            $table->decimal('final_payment', 12, 2)->nullable()->after('down_payment');
            $table->date('final_payment_date')->nullable()->after('final_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
            $table->dropColumn(['final_payment', 'final_payment_date']);
        });
    }
};
