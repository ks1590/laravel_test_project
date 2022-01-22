<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::create([
            'sku' => 'MS_TESTCODE',
            'display_name' => 'MS_ITEM_TEST',
            'category_id' => 1,
            'price' => 1000
        ]);
        Item::create([
            'sku' => '8437013926010',
            'display_name' => 'チョコフルーツ　マカダム 100g',
            'category_id' => 2,
            'price' => 1900
        ]);
        Item::create([
            'sku' => '8437013926072',
            'display_name' => 'チョコフルーツ　ブラウニー 100g',
            'category_id' => 2,
            'price' => 1800
        ]);
        Item::create([
            'sku' => '8437013926690',
            'display_name' => 'キューブボンボン ミニバー（4種）',
            'category_id' => 3,
            'price' => 2000
        ]);
        Item::create([
            'sku' => '8437013926720',
            'display_name' => 'キューブボンボン フルーツ（4種）',
            'category_id' => 3,
            'price' => 2500
        ]);
    }
}
