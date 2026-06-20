<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = ['name', 'description', 'base_cost', 'estimated_days'];

    protected $casts = [
        'base_cost' => 'float',
        'estimated_days' => 'integer',
    ];
}
