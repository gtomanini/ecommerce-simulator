<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function store(Request $request, string $orderId)
    {
        $user = $request->user();

        $order = Order::where('user_id', $user->id)->findOrFail($orderId);

        // Reject if this order already has a completed payment.
        if ($order->payment()->where('status', 'completed')->exists()) {
            return response()->json(['message' => 'This order has already been paid'], 422);
        }

        $isCard = in_array($request->input('method'), ['credit_card', 'debit_card']);

        $validated = $request->validate([
            'method' => ['required', Rule::in(['credit_card', 'debit_card', 'pix'])],
            'card_holder' => [Rule::requiredIf($isCard), 'nullable', 'string', 'max:255'],
            'card_number' => [Rule::requiredIf($isCard), 'nullable', 'string', 'regex:/^\d{13,19}$/'],
            'card_expiry' => [Rule::requiredIf($isCard), 'nullable', 'string', 'regex:#^\d{2}/\d{2}$#'],
            'card_cvv' => [Rule::requiredIf($isCard), 'nullable', 'string', 'regex:/^\d{3,4}$/'],
        ]);

        // Build a safe, simulated payment_data payload (never store full PAN/CVV).
        $paymentData = match ($validated['method']) {
            'credit_card', 'debit_card' => [
                'card_holder' => $validated['card_holder'],
                'card_last4' => substr($validated['card_number'], -4),
                'card_expiry' => $validated['card_expiry'],
            ],
            'pix' => [
                'pix_key' => 'PIX-' . strtoupper(bin2hex(random_bytes(8))),
            ],
        };

        $payment = $order->payment()->create([
            'method' => $validated['method'],
            'amount' => $order->total,
            'status' => 'completed',
            'transaction_id' => 'TXN-' . date('YmdHis') . '-' . uniqid(),
            'payment_data' => $paymentData,
        ]);

        $order->update(['status' => 'confirmed']);

        AuditService::logAction(
            $user,
            'payment_completed',
            "Payment for order {$order->order_number} completed via {$validated['method']} (R$ {$order->total})",
            'Payment',
            $payment->id,
            [
                'order_number' => $order->order_number,
                'method' => $validated['method'],
                'amount' => $order->total,
            ]
        );

        return response()->json($order->fresh()->load('items.product', 'shippingMethod', 'payment'));
    }
}
