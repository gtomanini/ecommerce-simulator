<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\AuditService;
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
            'delivery_zip' => 'required|string',
        ]);

        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->with('items')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Remember the delivery details on the user's profile so the
        // checkout form comes pre-filled on their next purchase.
        $user->update([
            'name' => $validated['buyer_name'],
            'phone' => $validated['buyer_phone'],
            'address' => $validated['delivery_address'],
            'city' => $validated['delivery_city'],
            'state' => $validated['delivery_state'],
            'zip' => $validated['delivery_zip'],
        ]);

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
            'delivery_zip' => $validated['delivery_zip'],
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

        // The order starts as 'pending' and awaits payment on the
        // dedicated payment screen (see PaymentController).

        // Clear cart
        $cart->items()->delete();

        AuditService::logAction(
            $user,
            'order_created',
            "Created order {$order->order_number} with {$order->items()->count()} items for R$ {$order->total}",
            'Order',
            $order->id,
            [
                'order_number' => $order->order_number,
                'subtotal' => $order->subtotal,
                'shipping_cost' => $order->shipping_cost,
                'total' => $order->total,
                'items_count' => $order->items()->count(),
                'shipping_method' => $order->shippingMethod->name,
            ]
        );

        return response()->json($order->load('items.product', 'shippingMethod'), 201);
    }
}
