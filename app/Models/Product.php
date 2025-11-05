<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // === TAMBAHKAN BLOK $fillable DI BAWAH INI ===
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image',
        'selling_price',
        'stock',
    ];
    // === SAMPAI SINI ===

    // Relasi Category (mungkin sudah ada)
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // Relasi Materials (mungkin sudah ada)
    public function materials() {
        return $this->belongsToMany(Material::class, 'product_materials')
                    ->withPivot('quantity_needed');
    }

    // Relasi OrderItems (mungkin sudah ada)
    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}