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
        // Settings table doesn't need schema change since it stores arbitrary key-values, 
        // but we'll register the migration so it runs cleanly and we'll save credentials as key-values.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
