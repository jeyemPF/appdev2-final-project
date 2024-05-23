<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menu,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 0, 
            'status' => 'pending',
        ]);

        $totalAmount = 0;

        // Create order items
        foreach ($request->items as $item) {
            $menu = Menu::find($item['menu_id']);
            $unitPrice = $menu->price;
            $quantity = (int) $item['quantity']; // Cast to integer
            
            $totalItemPrice = $unitPrice * $quantity;


            OrderItem::create([
                'items_id' => $menu->id,
                'order_id' => $order->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ]);

            $totalAmount += $totalItemPrice;
        }

        // Update the total amount of the order
        $order->update(['total_amount' => $totalAmount]);

        return response()->json([
            'message' => 'Order placed successfully',
            'order' => $order,
            'order_items' => $order->orderItems,
        ], 201);
    }
}
