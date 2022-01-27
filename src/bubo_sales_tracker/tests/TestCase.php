<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
//.envにDB_TEST_DATABASE=bubo_sales_tracker_testingを追記

    use CreatesApplication;
    use DatabaseTransactions;

    private static $isSetup = false;

    public function setUp(): void
    {
        parent::setUp();
        if (env('DB_TEST_DATABASE') == 'bubo_sales_tracker_testing' && self::$isSetup === false) {
            Artisan::call('migrate:fresh');
            Artisan::call(
                'db:seed', ['--class' => 'DatabaseSeeder']
            );
            self::$isSetup = true;
        }
    }
}
