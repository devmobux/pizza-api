<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function convoyPoints(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ConvoyPoint::class);
    }

}
