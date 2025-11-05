<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // Tambahkan semua kolom yang hilang, kita cek satu per satu
            if (!Schema::hasColumn('materials', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('materials', 'stock')) {
                $table->integer('stock')->default(0)->after('name');
            }
            if (!Schema::hasColumn('materials', 'unit')) {
                $table->string('unit')->after('stock');
            }
            if (!Schema::hasColumn('materials', 'cost_price')) {
                $table->decimal('cost_price', 10, 2)->default(0)->after('unit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // Hapus kolom jika ada
            if (Schema::hasColumn('materials', 'name')) $table->dropColumn('name');
            if (Schema::hasColumn('materials', 'stock')) $table->dropColumn('stock');
            if (Schema::hasColumn('materials', 'unit')) $table->dropColumn('unit');
            if (Schema::hasColumn('materials', 'cost_price')) $table->dropColumn('cost_price');
        });
    }
};