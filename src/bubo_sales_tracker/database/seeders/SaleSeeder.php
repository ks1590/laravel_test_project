<?php

namespace Database\Seeders;

use App\Models\Sale;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sales')->insert([
            [
                'shop_id' => 1,
                'date' => date("Y-m-d", strtotime('-3 days')),
                'is_sumaregi_committed' => false,
                'transaction_count' => 100,
            ],
            [
                'shop_id' => 1,
                'date' => date("Y-m-d", strtotime('-2 days')),
                'is_sumaregi_committed' => false,
                'transaction_count' => 150,
            ],
            [
                'shop_id' => 2,
                'date' => date("Y-m-d", strtotime('-1 day')),
                'is_sumaregi_committed' => true,
                'transaction_count' => 200,
            ],
            [
                'shop_id' => 2,
                'date' => date("Y-m-d", strtotime('-2 days')),
                'is_sumaregi_committed' => true,
                'transaction_count' => 250,
            ],
            [
                'shop_id' => 2,
                'date' => date("Y-m-d", strtotime('-3 days')),
                'is_sumaregi_committed' => true,
                'transaction_count' => 300,
            ],
            [
                'shop_id' => 2,
                'date' => date("Y-m-d", strtotime('-4 days')),
                'is_sumaregi_committed' => true,
                'transaction_count' => 350,
            ],
        ]);
    }
}
