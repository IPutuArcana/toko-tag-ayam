<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')->after('id')->constrained('categories');
            }
            if (!Schema::hasColumn('products', 'name')) {
                $table->string('name')->after('category_id');
            }
            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'selling_price')) {
                $table->decimal('selling_price', 10, 2)->default(0)->after('image');
            }
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0)->after('selling_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
            if (Schema::hasColumn('products', 'name')) $table->dropColumn('name');
            if (Schema::hasColumn('products', 'description')) $table->dropColumn('description');
            if (Schema::hasColumn('products', 'image')) $table->dropColumn('image');
            if (Schema::hasColumn('products', 'selling_price')) $table->dropColumn('selling_price');
            if (Schema::hasColumn('products', 'stock')) $table->dropColumn('stock');
        });
    }
};