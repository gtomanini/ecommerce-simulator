<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'base_cost', 'estimated_days'];

    protected $casts = [
        'base_cost' => 'float',
        'estimated_days' => 'integer',
    ];
}
