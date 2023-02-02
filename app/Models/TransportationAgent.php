<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportationAgent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function convoy(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Convoy::class);
    }

}
