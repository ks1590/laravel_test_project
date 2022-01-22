<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'test_user',
            'password' => Hash::make('Predevapp_1'),
            'shop_id' => null,
            'is_admin' => true
        ]);

        User::create([
            'username' => 'test_user2',
            'password' => Hash::make('Predevapp_1'),
            'shop_id' => 1,
            'is_admin' => false
        ]);
    }
}
