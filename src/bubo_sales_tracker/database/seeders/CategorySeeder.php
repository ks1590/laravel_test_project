<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => "アドベントカレンダー",
        ]);
        Category::create([
            'name' => "チョコフルーツ",
        ]);
        Category::create([
            'name' => "キューブボンボン",
        ]);
        Category::create([
            'name' => "チョコスカルプチャー",
        ]);
        Category::create([
            'name' => "チョコロック",
        ]);
    }
}
