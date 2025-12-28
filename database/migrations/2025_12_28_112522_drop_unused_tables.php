<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Disable checks (Safety First)
        Schema::disableForeignKeyConstraints();

        // 2. Drop the tables you don't need
        // (Check your database if names are plural 'materials' or singular 'material')
        Schema::dropIfExists('productions'); 
        Schema::dropIfExists('production_details'); // If you had a detail table
        Schema::dropIfExists('materials');
        
        // 3. Re-enable checks
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Ideally, we would recreate tables here, but since we are deleting them 
        // permanently, we can leave this empty or throw an exception.
    }
};
