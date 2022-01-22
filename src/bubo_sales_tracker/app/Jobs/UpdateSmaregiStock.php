<?php

namespace App\Jobs;

use App\Facades\Chatwork;
use App\Http\Controllers\SmaregiController;
use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateSmaregiStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $smaregi;
    private $sale;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sale)
    {
        $this->smaregi = new SmaregiController;
        $this->sale = $sale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->updateStock($this->sale);
        } catch (\Exception $exception) {
            $this->sale->is_processing = false;
            $this->sale->save();
            $this->sendFailedMessage($exception);
        }
    }

    /**
     * Update Product Stock
     *
     * @param Sale $sale
     * @return \Illuminate\Http\RedirectResponse
     */
    private function updateStock(Sale $sale)
    {
        $sumaregi_tenpo_id = $sale->shop->sumaregi_tenpo_id;
        $products = $this->smaregi->fetchProductsByCategory();
        $stock_rows = [];

        foreach ($sale->saleDetails as $saleDetail) {
            foreach ($products as $product) {
                if ($product['productCode'] == $saleDetail["sku"]) {
                    $productId = $product['productId'];
                    break;
                }
            }

            $stock = [
                "storeId" => $sumaregi_tenpo_id,
                "productId" => $productId,
                "stockAmount" => "-" . $saleDetail["quantity"],
                "stockDivision" => "11"
            ];
            array_push($stock_rows, $stock);
        }

        $params = [
            "proc_info" => [
                "proc_division" => "U",
                "proc_detail_division" => "2"
            ],
            "data" => [
                [
                    "table_name" => "Stock",
                    "rows" => $stock_rows
                ]
            ]
        ];

        $query = [
            "proc_name" => "stock_upd",
            "params" => json_encode($params)
        ];

        $response = $this->smaregi->buildClient($query);

        if (array_key_exists('error_code', $response)) {
            $chatworktitle = 'Bubo Sales Tracker [Smaregi API Call Stock Update FAIL]';
            $chatworkbody = "Failed to Update Smaregi Stock."
                . "\n[error_code] " . $response['error_code']
                . "\n[error] " . $response['error']
                . "\n[error_description] " . $response['error_description']
                . "\n[previous_url] " . url()->previous()
                . "\n[current_url] " . url()->current();
            Chatwork::postToLogRoom($chatworktitle, $chatworkbody);
            $sale->is_processing = false;
            $sale->save();
        } else {
            $sale->is_processing = false;
            $sale->is_sumaregi_committed = true;
            $sale->save();
            Log::debug('Successfully Updated Smaregi Stock.');
        }
    }

    public function sendFailedMessage(\Exception $exception): void
    {
        $chatworktitle = "Bubo Sales Tracker [Update Smaregi Stock JOB FAIL]";
        $chatworkbody = "Failed to Update Smaregi Stock."
            . "\n[error-code] " . $exception->getCode()
            . "\n[error-mssg] " . $exception->getMessage();

        Chatwork::postToLogRoom($chatworktitle, $chatworkbody);
    }
}
