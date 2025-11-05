<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_materials', function (Blueprint $table) {
            // Tambahkan kolom yang hilang
            if (!Schema::hasColumn('product_materials', 'product_id')) {
                $table->foreignId('product_id')->after('id')->constrained('products');
            }
            if (!Schema::hasColumn('product_materials', 'material_id')) {
                $table->foreignId('material_id')->after('product_id')->constrained('materials');
            }
            if (!Schema::hasColumn('product_materials', 'quantity_needed')) {
                $table->decimal('quantity_needed', 8, 2)->default(0)->after('material_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_materials', function (Blueprint $table) {
            // Hapus dalam urutan terbalik
            if (Schema::hasColumn('product_materials', 'quantity_needed')) {
                $table->dropColumn('quantity_needed');
            }
            if (Schema::hasColumn('product_materials', 'material_id')) {
                $table->dropForeign(['material_id']);
                $table->dropColumn('material_id');
            }
            if (Schema::hasColumn('product_materials', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
        });
    }
};