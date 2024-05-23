<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('orderItems.menu')->get();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menu,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $totalAmount = 0;

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => 0,
            'status' => 'pending',
        ]);

        foreach ($validatedData['items'] as $item) {
            $menu = Menu::findOrFail($item['menu_id']);
            $quantity = (int) $item['quantity'];
            $unitPrice = (float) $menu->price;

            OrderItem::create([
                'order_id' => $order->id,
                'items_id' => $menu->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ]);

            $totalAmount += $quantity * $unitPrice;
        }

        // Update the total amount after all items are added
        $order->update(['total_amount' => $totalAmount]);

        return response()->json($order, 201);
    }

    public function show($id)
    {
        return Order::with('orderItems.menu')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,completed,canceled',
        ]);

        $order->update(['status' => $request->status]);

        return response()->json(['message' => 'Order updated successfully']);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
