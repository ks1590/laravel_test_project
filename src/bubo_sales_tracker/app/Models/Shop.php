<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_name',
        'sumaregi_tenpo_id',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
