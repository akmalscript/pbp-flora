<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'available',
        'image_url',
        'pre_order_duration',
        'active',
        'category_id',
    ];

    protected $attributes = [
        'available' => 0,
        'active' => 1,
    ];

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_items')
                    ->using(CartItem::class)
                    ->withPivot(['quantity']);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->using(OrderItem::class)
                    ->withPivot(['price', 'quantity', 'sub_total']);
    }
}