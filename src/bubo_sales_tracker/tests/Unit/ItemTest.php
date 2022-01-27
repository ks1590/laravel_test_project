<?php

namespace Tests\Unit;

use App\Models\Item;
use App\Models\Shop;
use Tests\TestCase;

class ItemTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItem()
    {
        $this->assertDatabaseHas('items', [
            'sku' => 'MS_TESTCODE'
        ]);
    }

    public function testShops()
    {
        $item = Item::first();
        $shops = $item->shops;

        $this->assertTrue($shops->first()->is(Shop::first()));
    }

    public function testMergeItemAndSmaregiStock()
    {
        $items = Item::all();
        $shops = Shop::all();
        $stocks = Item::mergeItemAndSmaregiStock($shops, $items);

        $this->assertFalse(is_null($stocks));
    }
}
