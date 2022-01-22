<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sale_details')->insert(array(
            0 =>
                array(
                    'sale_id'=> 1,
                    'sku' => '8437013926072',
                    'quantity' => 1,
                    'amount' => 1000,
                ),
            1 =>
                array(
                    'sale_id'=> 1,
                    'sku' => '8437013926010',
                    'quantity' => 10,
                    'amount' => 1000,
                ),
            2 =>
                array(
                    'sale_id'=> 1,
                    'sku' => '8437013926720',
                    'quantity' => 100,
                    'amount' => 1000,
                ),
            3 =>
                array(
                    'sale_id'=> 2,
                    'sku' => 'MS_TESTCODE',
                    'quantity' => 2,
                    'amount' => 200,
                ),
            4 =>
                array(
                    'sale_id'=> 2,
                    'sku' => 'MS_TESTCODE',
                    'quantity' => 20,
                    'amount' => 2000,
                ),
            5 =>
                array(
                    'sale_id'=> 2,
                    'sku' => 'MS_TESTCODE',
                    'quantity' => 200,
                    'amount' => 20000,
                ),
            6 =>
                array(
                    'sale_id'=> 3,
                    'sku' => 'MS_TESTCODE',
                    'quantity' => 3,
                    'amount' => 300,
                ),
        ));
    }
}
