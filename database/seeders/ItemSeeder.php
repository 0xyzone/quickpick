<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::create([
            'category_id' => '1',
            'name' => 'Momo',
            'price' => 80
        ]);
        Item::create([
            'category_id' => '1',
            'name' => 'Chowmein',
            'price' => 100
        ]);
        Item::create([
            'category_id' => '2',
            'name' => 'Coke',
            'price' => 60
        ]);
    }
}
