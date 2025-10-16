<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
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

        // Buat order untuk user1
        $order1 = Order::create([
            'status' => 'pending',
            'note' => 'Tolong dikirim cepat',
            'total' => 0, // nanti bisa dihitung dari pivot
            'delivery' => true,
            'address' => 'Jl. Sudirman No. 10, Jakarta',
            'user_id' => $user1->id,
        ]);

        // Tambahkan produk ke order1 dengan pivot price, quantity, sub_total
        $order1->products()->attach([
            $products[0]->id => [
                'price' => $products[0]->price,
                'quantity' => 2,
                'sub_total' => $products[0]->price * 2
            ],
            $products[1]->id => [
                'price' => $products[1]->price,
                'quantity' => 1,
                'sub_total' => $products[1]->price
            ],
        ]);

        // Update total order1
        $order1->total = $order1->products->sum('pivot.sub_total');
        $order1->save();

        // Buat order untuk user2
        $order2 = Order::create([
            'status' => 'completed',
            'note' => 'Barang diterima dengan baik',
            'total' => 0,
            'delivery' => true,
            'address' => 'Jl. Thamrin No. 5, Jakarta',
            'user_id' => $user2->id,
        ]);

        $order2->products()->attach([
            $products[2]->id => [
                'price' => $products[2]->price,
                'quantity' => 1,
                'sub_total' => $products[2]->price
            ],
            $products[3]->id => [
                'price' => $products[3]->price,
                'quantity' => 3,
                'sub_total' => $products[3]->price * 3
            ],
        ]);

        $order2->total = $order2->products->sum('pivot.sub_total');
        $order2->save();
    }
}
