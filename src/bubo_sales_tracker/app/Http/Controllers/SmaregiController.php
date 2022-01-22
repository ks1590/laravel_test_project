<?php

namespace App\Http\Controllers;

use App\Facades\Chatwork;
use App\Utils\ClientBuilder;
use App\Models\Sale;
use App\Jobs\UpdateSmaregiStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use function PHPUnit\Framework\isEmpty;

class SmaregiController extends Controller
{
    private $headers;
    private $url = "https://webapi.smaregi.jp/access/";

    public function __construct()
    {
        $this->headers = [
            "X-access-token" => Config::get('app.smaregi.access_token'),
            "X-contract-id" => Config::get('app.smaregi.contract_id'),
            "Content-Type" => 'application/x-www-form-urlencoded'
        ];
    }

    /**
     * Template Smaregi API Call
     *
     * @param array
     * @return array
     */
    public function buildClient(array $query): array
    {
        $result = ClientBuilder::make()
            ->header($this->headers)
            ->noErrors()
            ->post()
            ->to($this->url)
            ->with($query)
            ->fire();

        $response = json_decode($result, true);

        return $response;
    }

    /**
     * Check Smaregi API Call Error Response
     *
     * @param array
     */
    public function checkErrorResponse(array $response): void
    {
        if (array_key_exists('error_code', $response)) {
            $chatworktitle = 'Bubo Sales Tracker [Smaregi API Call Fetch Products FAIL]';
            $chatworkbody = "Failed to Display Smaregi Products."
                . "\n[error_code] " . $response['error_code']
                . "\n[error] " . $response['error']
                . "\n[error_description] " . $response['error_description']
                . "\n[previous_url] " . url()->previous()
                . "\n[current_url] " . url()->current();
            Chatwork::postToLogRoom($chatworktitle, $chatworkbody);
            throw new \Exception('スマレジ店舗在庫の取得に失敗したため、商品一覧ページを開けませんでした。');
        }
    }

    /**
     * Fetch Smaregi Product Stock by Store
     *
     * @param array $stores
     * @return array
     */
    public function fetchStockByStore(array $stores): array
    {
        $query = [
            "proc_name" => "stock_ref",
            "params" => '{"table_name":"Stock"}'
        ];

        $response = $this->buildClient($query);
        $this->checkErrorResponse($response);
        $stocks = $response['result'];

        $target_stores = [];

        foreach ($stores as $store) {
            $rows = [
                'shop_name' => $store['shop_name'],
                'sumaregi_tenpo_id' => $store['sumaregi_tenpo_id'],
            ];

            array_push($target_stores, $rows);
        }

        $store_stock_list = [];
        foreach ($target_stores as $store) {
            foreach ($stocks as $stock) {
                if ($store['sumaregi_tenpo_id'] == $stock['storeId']) {
                    $row = [
                        'storeId' => $stock['storeId'],
                        'productId' => $stock['productId'],
                        'stockAmount' => $stock['stockAmount'],
                    ];

                    array_push($store_stock_list, $row);
                }
            }
        }

        return $store_stock_list;
    }

    /**
     * Fetch Smaregi Product Category
     *
     * @return array
     */
    public function fetchCategories(): array
    {
        $query = [
            "proc_name" => "category_ref",
            "params" => '{"fields":["categoryId","categoryName"],"conditions":[{"categoryName like":"MS%"}],"table_name":"Category"}'
        ];

        $response = $this->buildClient($query);
        $this->checkErrorResponse($response);

        return array_column($response['result'], 'categoryId');
    }

    /**
     * Fetch Smaregi Products by Category
     *
     * @return array
     */
    public function fetchProductsByCategory(): array
    {
        $query = [
            "proc_name" => "product_ref",
            "params" => '{"fields":["productId","productCode","productName","categoryId"],"table_name":"Product"}'
        ];

        $response = $this->buildClient($query);
        $this->checkErrorResponse($response);
        $products = $response['result'];

        $categories = $this->fetchCategories();

        $product_list = [];
        foreach ($categories as $category) {
            foreach ($products as $product) {
                if ($category == $product['categoryId']) {
                    $row = [
                        'productId' => $product['productId'],
                        'productName' => $product['productName'],
                        'productCode' => $product['productCode'],
                    ];
                    array_push($product_list, $row);
                }
            }
        }

        return $product_list;
    }

    /**
     * Merge Store Stock and Product Code
     *
     * @param array $store_stock_list
     * @param array $product_list
     * @return array
     */
    public function mergeStoreStockAndProductCode(array $store_stock_list, array $product_list): array
    {
        $stocks = [];

        foreach ($store_stock_list as $store_stock) {
            foreach ($product_list as $product) {
                if ($store_stock['productId'] == $product['productId']) {
                    $stock = [
                        'storeId' => $store_stock['storeId'],
                        'productName' => $product['productName'],
                        'productCode' => $product['productCode'],
                        'stockAmount' => $store_stock['stockAmount']
                    ];
                    array_push($stocks, $stock);
                }
            }
        }

        return $stocks;
    }

    /**
     * Update Product Stock
     *
     * @param Sale $sale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStock(Sale $sale)
    {
        if ($sale->is_processing || $sale->is_sumaregi_committed) {
            return redirect()->back()->with('danger', $sale->shop->shop_name.'の' . date('Y/m/d', strtotime($sale->date)) . '付データはすでに提出済みです。');
        }

        try {
            $chatworktitle = 'Bubo Sales Tracker [Smaregi Product Stock Update Button Clicked]';
            $chatworkbody = "スマレジ在庫更新ボタンが押されました。"
                . "\n[shop_name] " . $sale->shop->shop_name
                . "\n[sales_date] " . $sale->date;
            Chatwork::postToLogRoom($chatworktitle, $chatworkbody);

            UpdateSmaregiStock::dispatch($sale)->onQueue('update');
            $sale->is_processing = true;
            $sale->save();

            return redirect()->to(route('sale.index'))->with('success', date('Y/m/d', strtotime($sale->date)) . 'の' . $sale->shop->shop_name . '売上をスマレジに反映しました。');
        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }

    /**
     * Update All Product Stocks
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStockAll()
    {
        $sales = Sale::where(['is_processing' => false,'is_sumaregi_committed' => false])->get();

        if ($sales->count() < 1) {
            return redirect()->back()->with('danger', '全売上データはスマレジに反映済みです。');
        }

        try {
            foreach ($sales as $sale) {
                if ($sale->is_processing || $sale->is_sumaregi_committed) {
                    continue;
                }
                $chatworktitle = 'Bubo Sales Tracker [Smaregi Product Stock Update Button Clicked]';
                $chatworkbody = "スマレジ在庫更新ボタンが押されました。"
                    . "\n[shop_name] " . $sale->shop->shop_name
                    . "\n[sales_date] " . $sale->date;
                Chatwork::postToLogRoom($chatworktitle, $chatworkbody);

                UpdateSmaregiStock::dispatch($sale)->onQueue('update');
                $sale->is_processing = true;
                $sale->save();
            }

            return redirect()->to(route('sale.index'))->with('success', '全ての売上をスマレジに反映しました。');
        } catch (\Exception $e) {
            logs()->error($e);
            return redirect()->back()->with('danger', $e->getMessage());
        }
    }
}
