<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'hmo_id',
        'name',
        'strategy',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function hmo(): BelongsTo
    {
        return $this->belongsTo(Hmo::class);
    }
}
