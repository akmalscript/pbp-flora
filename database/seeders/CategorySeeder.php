<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Elektronik',
            'description' => 'Perangkat elektronik seperti HP, Laptop, dll.'
        ]);

        Category::create([
            'name' => 'Pakaian',
            'description' => 'Baju, celana, dan aksesoris fashion.'
        ]);

        Category::create([
            'name' => 'Makanan & Minuman',
            'description' => 'Snack, minuman, dan bahan makanan.'
        ]);

        Category::create([
            'name' => 'Buku',
            'description' => 'Buku fiksi, non-fiksi, dan buku anak-anak.'
        ]);
    }
}
