<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Cart extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'carts';

    protected $fillable = [
        'user_id',
    ];

    protected $attributes = [
        'active' => 1,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_items')
                    ->using(CartItem::class)
                    ->withPivot(['quantity']);
    }
}