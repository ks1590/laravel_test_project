<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionStartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'developer',
            'password' => Hash::make('Predevapp_1'),
            'shop_id' => null,
            'is_admin' => true
        ]);
    }
}
