<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'inr_price', 'usd_price', 'billing_cycle'
    ];

    protected $casts = [
        'inr_price' => 'decimal:2',
        'usd_price' => 'decimal:2',
    ];
}
