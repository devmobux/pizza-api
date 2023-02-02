<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'last_name',
        'first_name',
        'gender',
        'phone',
        'image_profile'
    ];

    public function convoyPoints(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ConvoyPoint::class);
    }

}
