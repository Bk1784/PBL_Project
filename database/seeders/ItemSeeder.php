<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run()
    {
        Item::create([
            'name' => 'Produk A',
            'sold_count' => 10,
            'price' => 10000,
            'total_revenue' => 100000,
            'sale_date' => now(),
        ]);

        Item::create([
            'name' => 'Produk B',
            'sold_count' => 5,
            'price' => 15000,
            'total_revenue' => 75000,
            'sale_date' => now(),
        ]);
    }
}