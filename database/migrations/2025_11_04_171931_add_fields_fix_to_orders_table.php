<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained('users');
            }
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending')->after('customer_name');
            }
            if (!Schema::hasColumn('orders', 'total_price')) {
                $table->decimal('total_price', 10, 2)->default(0)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('orders', 'customer_name')) $table->dropColumn('customer_name');
            if (Schema::hasColumn('orders', 'status')) $table->dropColumn('status');
            if (Schema::hasColumn('orders', 'total_price')) $table->dropColumn('total_price');
        });
    }
};