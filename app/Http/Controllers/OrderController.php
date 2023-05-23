<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    //

    public function createOrder(Request $request) {
        try {
            $order = Order::create([
                'items_price' => $request['itemsPrice'],
                'shipping_price' => $request['shippingPrice'],
                'tax_price' => $request['taxPrice'],
                'total_price' => $request['totalPrice'],
                'payment_method' => $request['payment_method'],
                'seller_id' => $request['seller'],
                'user_id' => $request['user_id'],
            ]);
            $shipping_address = DB::table('shipping_addresses')->insert([
                'full_name' => $request['shippingAddress']['fullName'],
                'address' => $request['shippingAddress']['address'],
                'city' => $request['shippingAddress']['city'],
                'postal_code' => $request['shippingAddress']['postalCode'],
                'country' => $request['shippingAddress']['country'],
                'order_id' => $order['id'],
            ]);
            //$items = $request['cartItems'].length;
            $items = $request['orderItems'];
            
            foreach($items as $item) {
                
                DB::table('order_items')->insert([
                    'name' => $item['name'],
                    'cart_qty' => $item['cartQty'],
                    'image' => $item['image'],
                    'price' => $item['price'],
                    'order_id' => $order['id'],
                ]);
            }

            return response()->json(['order' => $order, 'message' => 'Order created successfully!']);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'message'=>'Something goes wrong while inserting order!'
            ],500);
        }
        
    }

    
    public function getOrder($id) {
        $order = DB::table('orders')
        ->join('shipping_addresses', 'orders.id', '=', 'shipping_addresses.order_id')
        ->select('orders.*', 'shipping_addresses.id as shipping_id', 'shipping_addresses.full_name', 'shipping_addresses.address',
                'shipping_addresses.postal_code', 'shipping_addresses.city', 'shipping_addresses.country')
        ->where('orders.id', '=', $id)->get()->first();
        return $order;
    }

    public function getOrderItems($id) {
        $order_items = DB::table('order_items')
        ->where('order_items.order_id', '=', $id)->get();
        return $order_items;
    }

    public function getOrderList(Request $request) {
        $orders;
        if($request['is_admin']) {
            $orders = DB::table('orders')
                        ->join('users', 'orders.user_id', '=', 'users.id')
                        ->select('orders.*', 'users.name as user_name')->get();
        } else {
            $orders = DB::table('orders')
                        ->join('users', 'orders.user_id', '=', 'users.id')
                        ->select('orders.*', 'users.name as user_name')->where('orders.seller_id', '=', $request['id'])->get();
        }
        return $orders;
    }

    public function getOrderHistory($id) {
        $orders = DB::table('orders')
                            ->select('orders.*')->where('orders.user_id', '=', $id)->get();
        return $orders;
    }

    public function deleteOrder($id) {
        $order = Order::find($id);
        if($order) {
             DB::table('orders')->where('id', $id)->delete();
            return response()->json(['order' => $order, 'message' => 'Order delete success']);
        } else {
            return response()->json(['message' => 'Order not found', 401]);
        }
    }

    public function payOrder(Request $request, $id) {

        $order = Order::find($id);
        if($order) {
             DB::table('orders')->where('id', $id)->update([
            'is_paid' => true,
            'paid_at' => now(),
            'updated_at' => now(),
            ]);
            DB::table('payment_results')->insert([
                'status' => $request['status'],
                'update_time' => $request['update_time'],
                'email_address' => $request['email_address'],
                'order_id' => $id,
            ]);
            return response()->json(['order' => $order, 'message' => 'Order pay success']);
        } else {
            return response()->json(['message' => 'Order not found', 401]);
        }
    }

    public function deliverOrder($id) {

        $order = Order::find($id);
        if($order) {
             DB::table('orders')->where('id', $id)->update([
            'is_delivered' => true,
            'delivered_at' => now(),
            'updated_at' => now(),
            ]);
            return response()->json(['order' => $order, 'message' => 'Order deliver success']);
        } else {
            return response()->json(['message' => 'Order not found', 401]);
        }
    }

}
