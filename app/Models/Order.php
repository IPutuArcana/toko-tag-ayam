<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Izinkan kolom ini diisi massal
    protected $fillable = [
        'user_id',
        'customer_name',
        'status',
        'total_price',
    ];

    // Relasi ke User (Kasir)
    public function user() {
        return $this->belongsTo(User::class)->withTrashed();
    }

    // Relasi ke Item Pesanan
    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        // One Order creates One Financial Entry
        return $this->hasOne(Transaction::class);
    }
}