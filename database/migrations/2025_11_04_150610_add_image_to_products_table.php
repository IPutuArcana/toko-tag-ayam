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
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan semua kolom yang hilang dari Fase 2
            // Kita tidak pakai after() lagi

            $table->foreignId('category_id')->constrained('categories');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('selling_price', 10, 2)->default(0);
            $table->integer('stock')->default(0);

            // Dan tambahkan kolom 'image' (tujuan awal kita)
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus semua kolom yang kita tambahkan
            $table->dropColumn('image');
            $table->dropColumn('stock');
            $table->dropColumn('selling_price');
            $table->dropColumn('description');
            $table->dropColumn('name');

            // Hapus foreign key & kolomnya
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};