<?php

namespace App\Models;

use App\Http\Controllers\SmaregiController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $primaryKey = 'sku';
    public $incrementing = false;

    protected $fillable = [
        "sku",
        "display_name",
        "category_id",
        "price"
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class);
    }

    public function mergeItemAndSmaregiStock($shops, $items)
    {
        $smaregi = new SmaregiController;
        $store_stock_list = $smaregi->fetchStockByStore($shops->toArray());
        $product_list = $smaregi->fetchProductsByCategory();
        $smaregi_stocks = $smaregi->mergeStoreStockAndProductCode($store_stock_list, $product_list);

        $all_shop_stock_list = [];
        foreach ($shops as $shop){
            $shop_stock_list = [];
            foreach ($smaregi_stocks as $stock){
                if ($shop->sumaregi_tenpo_id == $stock['storeId']){
                    array_push($shop_stock_list, $stock);
                }
            }
            foreach ($items as $item) {
                $stock_search_result = array_search($item->sku, array_column($shop_stock_list, 'productCode'));

                if ($stock_search_result === false){
                    $row = [
                        'storeId' => $shop->sumaregi_tenpo_id,
                        'productName' => $item->display_name,
                        'productCode' => $item->sku,
                        'stockAmount' => 0
                    ];
                    array_push($shop_stock_list, $row);
                }
            }
            array_push($all_shop_stock_list, $shop_stock_list);
        }

        $stocks = [];
        foreach ($all_shop_stock_list as $shop_stock) {
            foreach ($shop_stock as $item) {
                array_push($stocks, $item);
            }
        }
        return $stocks;
    }
}
