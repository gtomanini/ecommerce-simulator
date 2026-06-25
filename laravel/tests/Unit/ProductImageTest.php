<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_marking_an_image_primary_demotes_the_others(): void
    {
        $product = Product::factory()->create();
        $first = ProductImage::factory()->create(['product_id' => $product->id, 'is_primary' => true]);
        $second = ProductImage::factory()->create(['product_id' => $product->id, 'is_primary' => true]);

        $this->assertFalse($first->fresh()->is_primary);
        $this->assertTrue($second->fresh()->is_primary);
        $this->assertSame(1, $product->images()->where('is_primary', true)->count());
    }

    public function test_primary_flag_is_scoped_per_product(): void
    {
        $productA = Product::factory()->create();
        $productB = Product::factory()->create();

        $imageA = ProductImage::factory()->create(['product_id' => $productA->id, 'is_primary' => true]);
        $imageB = ProductImage::factory()->create(['product_id' => $productB->id, 'is_primary' => true]);

        // Different products keep their own primary image.
        $this->assertTrue($imageA->fresh()->is_primary);
        $this->assertTrue($imageB->fresh()->is_primary);
    }

    public function test_is_primary_is_cast_to_boolean(): void
    {
        $image = ProductImage::factory()->create(['is_primary' => 1]);
        $this->assertIsBool($image->fresh()->is_primary);
    }
}
