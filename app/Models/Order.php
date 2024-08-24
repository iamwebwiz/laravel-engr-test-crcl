<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'hmo_id',
        'batch_id',
        'provider_name',
        'encounter_date',
        'total',
    ];

    protected $casts = [
        'encounter_date' => 'date',
    ];
}
