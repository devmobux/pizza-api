<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConvoyProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'weight',
        'weight_unit',
        'quantity',
        'quantity_suffix',
        'convoy_point_id',
        'product_id'
    ];

    public function convoyPoint(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ConvoyPoint::class);
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
