<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
        'description',
        'order_id',
        'transaction_date',
    ];

    // Relationship: A transaction might belong to an Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
