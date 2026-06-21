<?php

namespace Tests\Unit;

use App\Models\Achievement;
use App\Models\AuditLog;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariation;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_orders_carts_and_achievements(): void
    {
        $user = User::factory()->create();
        Order::factory()->create(['user_id' => $user->id]);
        Cart::factory()->create(['user_id' => $user->id]);
        UserAchievement::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->orders);
        $this->assertCount(1, $user->carts);
        $this->assertCount(1, $user->achievements);
    }

    public function test_category_has_many_products(): void
    {
        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        $this->assertCount(3, $category->products);
    }

    public function test_product_belongs_to_category_and_has_images_and_variations(): void
    {
        $product = Product::factory()->create();
        ProductImage::factory()->count(2)->create(['product_id' => $product->id]);
        ProductVariation::factory()->count(2)->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertCount(2, $product->images);
        $this->assertCount(2, $product->variations);
    }

    public function test_product_image_belongs_to_product(): void
    {
        $image = ProductImage::factory()->create();
        $this->assertInstanceOf(Product::class, $image->product);
    }

    public function test_product_variation_belongs_to_product(): void
    {
        $variation = ProductVariation::factory()->create();
        $this->assertInstanceOf(Product::class, $variation->product);
    }

    public function test_cart_relationships_and_total(): void
    {
        $cart = Cart::factory()->create();
        CartItem::factory()->create(['cart_id' => $cart->id, 'price' => 10.00, 'quantity' => 2]);
        CartItem::factory()->create(['cart_id' => $cart->id, 'price' => 5.00, 'quantity' => 3]);

        $this->assertInstanceOf(User::class, $cart->user);
        $this->assertCount(2, $cart->items);
        $this->assertEquals(35.00, $cart->fresh()->total());
    }

    public function test_cart_item_subtotal_accessor(): void
    {
        $item = CartItem::factory()->create(['price' => 25.50, 'quantity' => 4]);

        $this->assertEquals(102.00, $item->subtotal);
        $this->assertInstanceOf(Cart::class, $item->cart);
        $this->assertInstanceOf(Product::class, $item->product);
    }

    public function test_order_relationships(): void
    {
        $order = Order::factory()->create();
        OrderItem::factory()->count(2)->create(['order_id' => $order->id]);
        Payment::factory()->create(['order_id' => $order->id]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertCount(2, $order->items);
        $this->assertCount(1, $order->payment);
        $this->assertNotNull($order->shippingMethod);
    }

    public function test_order_item_relationships(): void
    {
        $item = OrderItem::factory()->create();
        $this->assertInstanceOf(Order::class, $item->order);
        $this->assertInstanceOf(Product::class, $item->product);
    }

    public function test_payment_belongs_to_order(): void
    {
        $payment = Payment::factory()->create();
        $this->assertInstanceOf(Order::class, $payment->order);
    }

    public function test_achievement_has_users(): void
    {
        $achievement = Achievement::factory()->create();
        UserAchievement::factory()->count(2)->create(['achievement_id' => $achievement->id]);

        $this->assertCount(2, $achievement->users);
    }

    public function test_user_achievement_relationships(): void
    {
        $userAchievement = UserAchievement::factory()->create();
        $this->assertInstanceOf(User::class, $userAchievement->user);
        $this->assertInstanceOf(Achievement::class, $userAchievement->achievement);
    }

    public function test_audit_log_belongs_to_user(): void
    {
        $auditLog = AuditLog::factory()->create();
        $this->assertInstanceOf(User::class, $auditLog->user);
    }

    public function test_casts_are_applied(): void
    {
        $product = Product::factory()->create(['price' => '99.90', 'stock' => '5']);
        $this->assertIsFloat($product->price);
        $this->assertIsInt($product->stock);

        $item = CartItem::factory()->create(['variations' => ['size' => 'M']]);
        $this->assertIsArray($item->variations);
    }
}
