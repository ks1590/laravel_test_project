<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_index()
    {
        User::factory()->create();
        $response = $this->actingAs(User::first())->get(route('item.index'));

        $response->assertStatus(200);
    }

    public function test_item_create()
    {
        User::factory()->create();
        $response = $this->actingAs(User::first())->get(route('item.create'));

        $response->assertStatus(200);
    }

    public function test_item_store()
    {
        User::factory()->create();

        $response = $this->actingAs(User::first())->post(route('item.store'), [
            'category_id' => 1,
            'display_name' => 'Test Item',
            'sku' => '1234567890000',
            "price" => 1000
        ]);

        $response->assertStatus(302);
    }

    public function test_item_edit()
    {
        User::factory()->create();

        $item = Item::factory()->create();
        $response = $this->actingAs(User::first())->get(route('item.edit', [
            'item' => $item,
        ]));

        $response->assertStatus(200);
    }

    public function test_item_update()
    {
        User::factory()->create();

        $item = Item::factory()->create();
        $id = $item->id;
        $oldItem = $item->replicate();

        $response = $this->actingAs(User::first())->put(route('item.update',[
            'item' => $item
        ]),[
            'category_id' => 2,
            'display_name' => 'different_item',
        ]);

        $item = Item::find($id);

        $this->assertNotEquals($item->display_name, $oldItem->display_name);
        $response->assertStatus(302);
    }

    public function test_item_destroy()
    {
        User::factory()->create();

        $item = Item::factory()->create();

        $response = $this->actingAs(User::first())->delete(route('item.destroy',[
            'item' => $item
        ]));

        $this->assertDeleted($item);
        $response->assertStatus(302);
    }
}
