<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_index()
    {
        User::factory()->create();
        $response = $this->actingAs(User::first())->get(route('category.index'));

        $response->assertStatus(200);
    }

    public function test_category_create()
    {
        User::factory()->create();
        $response = $this->actingAs(User::first())->get(route('category.create'));

        $response->assertStatus(200);
    }

    public function test_category_store()
    {
        User::factory()->create();

        $response = $this->actingAs(User::first())->post(route('category.store'), [
            'name' => 'アドベントカレンダー',
        ]);

        $response->assertStatus(302);
    }

    public function test_category_edit()
    {
        User::factory()->create();

        $category = Category::factory()->create();
        $response = $this->actingAs(User::first())->get(route('category.edit', [
            'category' => $category
        ]));

        $response->assertStatus(200);
    }

    public function test_category_update()
    {
        User::factory()->create();

        $category = Category::factory()->create();
        $id = $category->id;
        $oldCategory = $category->replicate();

        $response = $this->actingAs(User::first())->put(route('category.update',[
            'category' => $category
        ]),[
            'name' => 'チョコフルーツ',
        ]);

        $category = Category::find($id);

        $this->assertNotEquals($category->name, $oldCategory->name);
        $response->assertStatus(302);
    }

    public function test_category_destroy()
    {
        User::factory()->create();

        $category = Category::factory()->create();

        $response = $this->actingAs(User::first())->delete(route('category.destroy',[
            'category' => $category
        ]));

        $this->assertDeleted($category);
        $response->assertStatus(302);
    }
}
