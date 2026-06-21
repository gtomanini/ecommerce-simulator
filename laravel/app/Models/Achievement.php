<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'icon', 'condition'];

    public function users(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }
}
