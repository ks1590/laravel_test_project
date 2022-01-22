<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_index()
    {
        User::factory()->create();
        $response = $this->actingAs(User::first())->get(route('shop.index'));

        $response->assertStatus(200);
    }

    public function test_shop_create()
    {
        User::factory()->create();
        $response = $this->actingAs(User::first())->get(route('shop.create'));

        $response->assertStatus(200);
    }

    public function test_shop_store()
    {
        User::factory()->create();

        $response = $this->actingAs(User::first())->post(route('shop.store'), [
            'shop_name' => 'Test Shop',
            'sumaregi_tenpo_id' => 1,
        ]);

        $response->assertStatus(302);
    }

    public function test_shop_edit()
    {
        User::factory()->create();

        $shop = Shop::factory()->create();
        $response = $this->actingAs(User::first())->get(route('shop.edit', [
            'shop' => $shop
        ]));

        $response->assertStatus(200);
    }

    public function test_shop_update()
    {
        User::factory()->create();

        $shop = Shop::factory()->create();
        $id = $shop->id;
        $oldShop = $shop->replicate();

        $response = $this->actingAs(User::first())->put(route('shop.update',[
            'shop' => $shop
        ]),[
            'shop_name' => 'different_shop_name',
            'sumaregi_tenpo_id' => 100,
        ]);

        $shop = Shop::find($id);

        $this->assertNotEquals($shop->shop_name, $oldShop->shop_name);
        $this->assertNotEquals($shop->sumaregi_tenpo_id, $oldShop->sumaregi_tenpo_id);
        $response->assertStatus(302);
    }

    public function test_shop_destroy()
    {
        User::factory()->create();

        $shop = Shop::factory()->create();

        $response = $this->actingAs(User::first())->delete(route('shop.destroy',[
            'shop' => $shop
        ]));

        $this->assertDeleted($shop);
        $response->assertStatus(302);
    }
}
