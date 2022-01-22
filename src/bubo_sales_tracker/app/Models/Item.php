<?php

namespace App\Models;

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
}
