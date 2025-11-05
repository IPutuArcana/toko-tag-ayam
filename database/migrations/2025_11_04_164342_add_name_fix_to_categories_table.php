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
        // Cek dulu apakah kolomnya sudah ada
        if (!Schema::hasColumn('categories', 'name')) {
            Schema::table('categories', function (Blueprint $table) {
                // Jika belum ada, tambahkan
                $table->string('name')->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // Hati-hati saat rollback, only if kolomnya ada
            if (Schema::hasColumn('categories', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};