<?php

namespace Tests\Unit;

use App\Models\Item;
use App\Models\Shop;
use Tests\TestCase;

class ShopTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testShop()
    {
        $this->assertDatabaseHas('shops', [
            'shop_name' => '表参道店'
        ]);
    }

    public function testitems()
    {
        $shop = Shop::first();
        $items = $shop->items;

        $this->assertTrue($items->first()->is(Item::first()));
    }
}
