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
            $table->dropColumn([
                'down_payment',
                'down_payment_date',
                'final_payment',
                'final_payment_date'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('down_payment', 12, 2)->nullable();
            $table->date('down_payment_date')->nullable();
            $table->decimal('final_payment', 12, 2)->nullable();
            $table->date('final_payment_date')->nullable();
        });
    }
};
