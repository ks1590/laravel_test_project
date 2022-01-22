<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_index()
    {
        User::factory()->create();
        $response = $this->actingAs(User::first())->get(route('user.index'));

        $response->assertStatus(200);
    }

    public function test_user_create()
    {
        User::factory()->create();
        $response = $this->actingAs(User::first())->get(route('user.create'));

        $response->assertStatus(200);
    }

    public function test_user_store()
    {
        User::factory()->create();

        $response = $this->actingAs(User::first())->post(route('user.store'), [
            'username' => 'Test User',
            'is_admin' => false,
            'shop_id' => 1,
            'password' => 'password'
        ]);

        $response->assertStatus(302);
    }

    public function test_user_edit()
    {
        $user = User::factory()->create();

        $response = $this->actingAs(User::first())->get(route('user.edit', [
            'user' => $user
        ]));

        $response->assertStatus(200);
    }

    public function test_user_update()
    {
        User::factory()->create();

        $another_user = User::factory()->create();
        $id = $another_user->id;
        $oldUserName = $another_user->replicate();

        $response = $this->actingAs(User::first())->put(route('user.update',[
            'user' => $another_user
        ]),[
            'username' => 'different_user_name',
            'shop_id' => 1
        ]);

        $user = User::find($id);

        $this->assertNotEquals($user->username, $oldUserName->username);
        $response->assertStatus(302);
    }

    public function test_user_destroy()
    {
        User::factory()->create();

        $another_user = User::factory()->create();

        $response = $this->actingAs(User::first())->delete(route('user.destroy',[
            'user' => $another_user
        ]));

        $this->assertDeleted($another_user);
        $response->assertStatus(302);
    }
}
