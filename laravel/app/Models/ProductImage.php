<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'image_url', 'alt_text', 'is_primary'];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    protected static function booted(): void
    {
        // Keep a single primary image per product: when one is marked
        // primary, demote the others. Uses a query update so it doesn't
        // re-trigger model events (no recursion).
        static::saved(function (ProductImage $image) {
            if ($image->is_primary) {
                static::where('product_id', $image->product_id)
                    ->whereKeyNot($image->getKey())
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
