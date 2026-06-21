<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\AuditService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)
                    ->with('items.product')
                    ->first();

        if (!$cart) {
            return response()->json([
                'id' => null,
                'items' => [],
                'total' => 0,
            ]);
        }

        return response()->json([
            'id' => $cart->id,
            'items' => $cart->items,
            'total' => $cart->items->sum(fn($item) => $item->price * $item->quantity),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variations' => 'nullable|array',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $user = $request->user();

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        $cartItem = CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $validated['product_id'],
            ],
            [
                'quantity' => CartItem::where('cart_id', $cart->id)
                                      ->where('product_id', $validated['product_id'])
                                      ->first()
                                      ?->quantity + $validated['quantity'] ?? $validated['quantity'],
                'price' => $product->price,
                'variations' => $validated['variations'] ?? [],
            ]
        );

        AuditService::logAction(
            $user,
            'cart_item_added',
            "Added {$validated['quantity']} x '{$product->name}' to cart",
            'CartItem',
            $cartItem->id,
            ['product_id' => $product->id, 'quantity' => $validated['quantity'], 'price' => $product->price]
        );

        return response()->json($cartItem, 201);
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cartItem = CartItem::findOrFail($id);

        if ($validated['quantity'] == 0) {
            $cartItem->delete();
            return response()->json(['message' => 'Item removed from cart']);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return response()->json($cartItem);
    }

    public function destroy(Request $request, string $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $user = $request->user();

        AuditService::logAction(
            $user,
            'cart_item_removed',
            "Removed '{$cartItem->product->name}' from cart",
            'CartItem',
            $cartItem->id
        );

        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }
}
