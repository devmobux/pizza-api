<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pizza extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image_url',
    ];

    protected $appends = [
        'picture',
    ];

    protected $with = [
        'ingredients'
    ];

    protected $hidden = [
        'image_url',
        'created_at',
        'updated_at',
    ];

    public function ingredients(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'pizza_ingredient');
    }


    /**
     * Get the pizza image_url.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function picture(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => env('APP_URL') . '/storage/' . $this->image_url,
        );
    }

}
