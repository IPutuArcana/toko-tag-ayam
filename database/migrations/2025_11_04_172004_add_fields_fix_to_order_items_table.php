<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'order_id')) {
                $table->foreignId('order_id')->after('id')->constrained('orders');
            }
            if (!Schema::hasColumn('order_items', 'product_id')) {
                $table->foreignId('product_id')->after('order_id')->constrained('products');
            }
            if (!Schema::hasColumn('order_items', 'quantity')) {
                $table->integer('quantity')->after('product_id');
            }
            if (!Schema::hasColumn('order_items', 'price_at_time_of_sale')) {
                $table->decimal('price_at_time_of_sale', 10, 2)->after('quantity');
            }
            if (!Schema::hasColumn('order_items', 'custom_text')) {
                $table->text('custom_text')->nullable()->after('price_at_time_of_sale');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
            if (Schema::hasColumn('order_items', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            if (Schema::hasColumn('order_items', 'quantity')) $table->dropColumn('quantity');
            if (Schema::hasColumn('order_items', 'price_at_time_of_sale')) $table->dropColumn('price_at_time_of_sale');
            if (Schema::hasColumn('order_items', 'custom_text')) $table->dropColumn('custom_text');
        });
    }
};