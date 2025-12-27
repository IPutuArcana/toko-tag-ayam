<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // Enum: 'income' (sales) or 'expense' (buying materials/bills)
            $table->enum('type', ['income', 'expense']); 
            
            $table->decimal('amount', 12, 2); // 12 digits, 2 decimals (Good for Rupiah)
            $table->string('description');
            
            // Link to an order (Nullable, because an electricity bill has no Order ID)
            $table->unsignedBigInteger('order_id')->nullable(); 
            
            // Date of transaction (Default to now)
            $table->date('transaction_date');

            $table->timestamps();
            
            // Optional: Foreign key constraint if you have an 'orders' table
            // $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }
};
