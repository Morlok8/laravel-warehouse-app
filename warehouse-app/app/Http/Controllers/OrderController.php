<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    //
    public function index(Request $request)
    {
        $orders = Order::all();

        $queryIndex = [];
        foreach($orders as $order){
           $queryIndex[] =  $order->id;
        }

        $baseQuery = DB::table('order_items')->join('products', 'order_items.product_id', '=', 'products.id')->join('stocks', 'order_items.product_id', '=', 'stocks.product_id')->whereIn('order_items.order_id', $queryIndex);

        if ($request->has('status')) {
            $baseQuery->where('status', $request->status);
        }

        if ($request->has('warehouse_id')) {
            $baseQuery->where('warehouse_id', $request->warehouse_id);
        }
        
        $result = $baseQuery->get();

        $orders = [];
        $products = [];
        foreach($result as $res){
            $orders[$res->order_id][] =
            [
                "product_id" => $res->product_id,
                "count" => $res->count,
                "name" => $res->name,
                "price" => $res->price,
                "warehouse_id" => $res->warehouse_id,
                "stock" => $res->stock,
            ]; 
        } 

        return response()->json($orders);
    }

    // create order method
    public function store(Request $request){
        // validate products with different conditions
        // if conditions fail, return the message with error, otherwise proceed
        if($this->validate_products($request->products, $request->warehouse_id) !== true)
            return response()->json([
                "message"=>$this->validate_products($request->products, $request->warehouse_id)
        ],401);

        $order = Order::create([
            "customer" => $request->customer,
            "warehouse_id" => $request->warehouse_id,
            "status" => "active",
        ]);

        foreach($request->products as $product){
            $this->changeStock($product['id'], $request->warehouse_id, $product['count'] * -1, "order_created", $order->id);

            DB::table('order_items')->insert([
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'count' => $product['count']
            ]);
        }

        return response()->json([
            "message" => [
                "Order added"
            ],
            
        ], 201);
    }

    //update order 
    public function update(Request $request, $id){
        if(!$request->client && empty($request->products)){
            return response()->json([
                "message" => [
                    "Sorry, only client list and items are updatable"
                ],  
            ], 401); 
        }
        $order = Order::where('id', $id)->first();

        if(!empty($request->products)){
            $order_items = DB::table('order_items')->where('order_id', $id)->get();
 
            foreach($order_items as $product){
                $stock = Stock::where('product_id', $product->product_id)->first();
                $stock->stock =  $stock->stock + $product->count;
                $stock->save();
            }
            DB::table('order_items')->where('order_id', $id)->delete();
            
            foreach($request->products as $product){
                $this->changeStock($product['id'], $order->warehouse_id, $product['count'] * -1, "order_updated", $id);

                DB::table('order_items')->insert([
                        'order_id' => $id,
                        'product_id' => $product['id'],
                        'count' => $product['count']
                ]);
            }
        }
        if(isset($request->client))
            $order->customer = $request->client;

        if($order->save())
        return response()->json([
                "message" => "Order updated successfully",
                "client" => $order
        ], 201); 
    }

    //cancel order 
    public function cancel(Request $request, $id){
        $order = Order::where('id', $id)->first();

        $order_items = DB::table('order_items')->where('order_id', $id)->get();
 
        foreach($order_items as $product){
            $this->changeStock($product->product_id, $order->warehouse_id, $product->count, "order_cancelled", $id);
        }
        
        $order->status = "cancelled";

        if($order->save()){
            return response()->json([
                "message" => "Order cancelled successfully",
                "client" => $order
            ], 201); 
        }
        else{
            return response()->json([
                "message" => "Something went wrong",
            ], 401); 
        }
    }

    //restore order
    public function restore(Request $request, $id)
    {
        $order = Order::where('id', $id)->first();

        if($order->status === "completed")
            return response()->json([
                "message" => "Sorry, completed orders cannot be restored",
            ], 201);  


        $order_items = DB::table('order_items')->where('order_id', $id)->get();
 
        foreach($order_items as $product){
            $this->changeStock($product->product_id, $order->warehouse_id, ($product->count) * -1, "order_restored", $id);
        }
        
        $order->status = "active";

        if($order->save()){
            return response()->json([
                "message" => "Order restored successfully",
                "client" => $order
            ], 201); 
        }
        else{
            return response()->json([
                "message" => "Something went wrong",
            ], 401); 
        }
    }

    //complete order
    public function complete(Request $request, $id){
        $order = Order::where('id', $id)->first();

        $order->status = "completed";

        $order->completed_at = now();

        if($order->save()){
            return response()->json([
                "message" => "Order completed successfully",
                "client" => $order
            ], 201); 
        }
        else{
            return response()->json([
                "message" => "Something went wrong",
            ], 401); 
        }
    }

    // validate products function
    protected function validate_products($products, $warehouse_id){
        $validity = true; 
        
        foreach($products as $product){
            $stock = Stock::where('product_id', $product['id'])->where('warehouse_id', $warehouse_id)->first();

            if(!$stock){
                $validity = false;
                return "Sorry, product ID ".$product['id']." is out of stock or doesn't exist in our database";
            }
            if($stock->stock < $product['count']){
                $validity = false;
                return "Sorry, product ID ".$product['id']." does not have the quantity you required. Avaliable quantity: ".$stock->stock;
            }
        }

        if($validity === true)
          return true;
        else
          return false; 
    }

    // change stock 
    private function changeStock($product_id, $warehouse_id, $count, $reason = "order_updated", $order_id = null){
        $stock = Stock::where('product_id', $product_id)->where('warehouse_id', $warehouse_id)->first();
        // changing the ammount of goods left
        $stock_value = $stock->stock + $count;

        $stock->update(['stock' => ($stock_value)]);

        $stock->save();

        // record movement
        StockMovement::create([
            'product_id' => $product_id,
            'warehouse_id' => $warehouse_id,
            'count' => $count,
            'reason' => $reason,
            'order_id' => $order_id,
        ]);

    }
}
