<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Sale extends Model
{
    protected $fillable = [
        'shop_id',
        'date',
        'is_processing',
        'is_sumaregi_committed',
    ];

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function totalAmount(): int
    {
        $result = 0;
        $saleDetails = $this->saleDetails;
        foreach ($saleDetails as $saleDetail) {
            $result = $result + $saleDetail["amount"];
        }
        return $result;
    }

    public function totalQuantity(): int
    {
        $result = 0;
        $saleDetails = $this->saleDetails;
        foreach ($saleDetails as $saleDetail) {
            $result += $saleDetail["quantity"];
        }
        return $result;
    }
}
