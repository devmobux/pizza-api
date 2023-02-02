<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConvoyPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'arrival_date',
        'convoy_id',
        'location_id',
        'provider_id',
        'failure_reason_id'
    ];

    public function convoy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Convoy::class);
    }

    public function location(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function provider(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function failureReason(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FailureReason::class);
    }

    public function convoyProduct(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ConvoyProduct::class);
    }

}
