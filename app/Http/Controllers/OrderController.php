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

    // Read all orders
    public function getAllOrders()
    {
        $orders = Order::with('orderItems')->get();
        return response()->json($orders, 200);
    }

    public function getUserOrders(Request $request)
    {
    // Get the authenticated user
    $user = Auth::user();

    // Fetch orders for the authenticated user
    $orders = Order::where('user_id', $user->id)
                    ->with('orderItems')
                    ->get();

    return response()->json($orders, 200);
    }


    // Read a single order
    public function getOrder($id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        return response()->json($order, 200);
    }

    // Update an order
    public function updateOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);
    
        $request->validate([
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menu,id', // Corrected table name
            'items.*.quantity' => 'required|integer|min:1',
        ]);
    
        $totalAmount = 0;
    
        // Remove old order items
        $order->orderItems()->delete();
    
        // Create new order items
        foreach ($request->items as $item) {
            $menu = Menu::find($item['menu_id']);
            $unitPrice = $menu->price;
            $quantity = (int) $item['quantity']; // Cast to integer
    
            $totalItemPrice = $unitPrice * $quantity;
    
            OrderItem::create([
                'items_id' => $menu->id, // Use 'items_id' instead of 'menu_id'
                'order_id' => $order->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
            ]);
    
            $totalAmount += $totalItemPrice;
        }
    
        // Update the total amount of the order
        $order->update(['total_amount' => $totalAmount]);
    
        return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order,
            'order_items' => $order->orderItems,
        ], 200);
    }

    // Delete an order
   public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'message' => 'Order cancel or delete successfully',
        ], 200);
    }


    // Delete a specific item from an order
    public function deleteOrderItem($orderId, $itemId)
    {
        $order = Order::findOrFail($orderId);
        $orderItem = $order->orderItems()->findOrFail($itemId);

        $totalItemPrice = $orderItem->unit_price * $orderItem->quantity;
        $orderItem->delete();

        // Update the total amount of the order
        $order->total_amount -= $totalItemPrice;
        $order->save();

        return response()->json([
            'message' => 'Order item deleted successfully',
            'order' => $order,
            'order_items' => $order->orderItems,
        ], 200);
    }
}



