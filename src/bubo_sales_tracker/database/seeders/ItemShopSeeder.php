<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\Shop;

class ItemShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shops = Shop::all();
        $items = Item::all();
        $item_shop_list = [];

        foreach ($shops as $shop ) {
            foreach ($items as $item ) {
                $row = [
                    'item_sku'=> $item->sku,
                    'shop_id' => $shop->id,
                ];
                array_push($item_shop_list, $row);
            }
        }

        DB::table('item_shop')->insert(
            $item_shop_list
        );
    }
}
