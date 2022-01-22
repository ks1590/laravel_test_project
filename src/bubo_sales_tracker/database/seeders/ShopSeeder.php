<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Shop;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shop::create([
            'shop_name' => "表参道店",
            'sumaregi_tenpo_id' => 1,
        ]);
        Shop::create([
            'shop_name' => "新横浜・プリアップ1F倉庫",
            'sumaregi_tenpo_id' => 2,
        ]);
    }
}
