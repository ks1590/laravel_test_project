<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SaleSeeder::class);
        $this->call(SaleDetailSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ShopSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ItemSeeder::class);
    }
}
