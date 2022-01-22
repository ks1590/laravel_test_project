<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item_shop')->insert(
            [
                'item_sku'=> 'MS_TESTCODE',
                'shop_id' => 1,
            ]
        );
    }
}
