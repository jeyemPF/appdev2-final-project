<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer,gcash',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Payment cannot be processed for this order'], 400);
        }

        // Check if the user has enough funds
        if ($request->amount > $order->user->gold) {
            // If not enough funds, cancel the order and return an error response
            $order->update(['status' => 'canceled']);
            return response()->json(['message' => 'Not enough cash to complete the payment. Order canceled.'], 400);
        }

        DB::transaction(function () use ($request, $order) {
            // Proceed with the payment
            Payment::create([
                'order_id' => $order->id,
                'payment_date' => now(),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
            ]);

            $order->update(['status' => 'completed']);

            // Deduct the amount from the user's gold
            $order->user->decrement('gold', $request->amount);
        });

        return response()->json(['message' => 'Payment processed successfully']);
    }
}
