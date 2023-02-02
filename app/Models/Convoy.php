<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convoy extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_date',
        'end_date',
        'transportation_agent_id',
    ];

    public function transportationAgent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TransportationAgent::class);
    }

    public function convoyPoints(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ConvoyPoint::class);
    }

}
