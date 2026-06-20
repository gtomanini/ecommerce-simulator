<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
                      ->with('items.product', 'shippingMethod')
                      ->orderByDesc('created_at')
                      ->paginate(10);

        return response()->json($orders);
    }

    public function show(Request $request, string $id)
    {
        $order = Order::where('user_id', $request->user()->id)
                     ->with('items.product', 'shippingMethod')
                     ->findOrFail($id);

        return response()->json($order);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'buyer_name' => 'required|string',
            'buyer_email' => 'required|email',
            'buyer_phone' => 'required|string',
            'delivery_address' => 'required|string',
            'delivery_city' => 'required|string',
            'delivery_state' => 'required|string',
            'delivery_zip_code' => 'required|string',
        ]);

        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->with('items')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);
        $shippingMethod = \App\Models\ShippingMethod::findOrFail($validated['shipping_method_id']);
        $shippingCost = $shippingMethod->base_cost;
        $total = $subtotal + $shippingCost;

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . date('YmdHis') . '-' . uniqid(),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'status' => 'pending',
            'buyer_name' => $validated['buyer_name'],
            'buyer_email' => $validated['buyer_email'],
            'buyer_phone' => $validated['buyer_phone'],
            'delivery_address' => $validated['delivery_address'],
            'delivery_city' => $validated['delivery_city'],
            'delivery_state' => $validated['delivery_state'],
            'delivery_zip_code' => $validated['delivery_zip_code'],
            'shipping_method_id' => $validated['shipping_method_id'],
            'estimated_delivery' => now()->addDays($shippingMethod->estimated_days),
        ]);

        foreach ($cart->items as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->price,
                'variations' => $cartItem->variations,
            ]);
        }

        // Simulate payment
        $order->payments()->create([
            'method' => 'credit_card',
            'amount' => $total,
            'status' => 'completed',
            'transaction_id' => 'TXN-' . date('YmdHis') . '-' . uniqid(),
        ]);

        // Clear cart
        $cart->items()->delete();

        return response()->json($order->load('items.product', 'shippingMethod'), 201);
    }
}
