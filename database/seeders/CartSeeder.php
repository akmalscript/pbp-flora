<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::where('email', 'user@example.com')->first();
        $user2 = User::where('email', 'admin@example.com')->first();

        // Ambil beberapa produk
        $products = Product::all();

        // Buat cart untuk user1
        $cart1 = Cart::create([
            'user_id' => $user1->id,
        ]);

        // Tambahkan produk ke cart1 dengan pivot quantity
        $cart1->products()->attach([
            $products[0]->id => ['quantity' => 2],
            $products[1]->id => ['quantity' => 1],
        ]);

        // Buat cart untuk user2
        $cart2 = Cart::create([
            'user_id' => $user2->id,
        ]);

        // Tambahkan produk ke cart2
        $cart2->products()->attach([
            $products[2]->id => ['quantity' => 3],
            $products[3]->id => ['quantity' => 1],
        ]);
    }
}
