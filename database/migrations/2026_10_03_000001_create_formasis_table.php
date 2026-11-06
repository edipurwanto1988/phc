<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::table('lokeres', function (Blueprint $table) {
            $table->foreignId('formasi_id')->nullable()->after('periode_id')->constrained('formasis')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lokeres', function (Blueprint $table) {
            $table->dropConstrainedForeignId('formasi_id');
        });
        Schema::dropIfExists('formasis');
    }
};
