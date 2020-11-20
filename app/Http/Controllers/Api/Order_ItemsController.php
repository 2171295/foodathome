<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrder_ItemsRequest;
use App\Http\Requests\UpdateOrder_ItemsRequest;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\OrderItems as OrderItemsResource;

class Order_ItemsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('page')) {
            return OrderItemsResource::collection(OrderItems::paginate(5));
        } else {
            return OrderItemsResource::collection(OrderItems::all());
        }
    }

    public function show(OrderItems $order_Items)
    {
        return new OrderItemsResource($order_Items);
    }

    public function store(StoreOrder_ItemsRequest $request){
        $order_Items = new OrderItems();
        $order_Items->fill($request->validated());
        $order_Items->save();
        return new OrderItemsResource($order_Items);
    }

    public function update(UpdateOrder_ItemsRequest $request, OrderItems $order_Items){
        $order_Items->update($request->validate());
        return new OrderItemsResource($order_Items);
    }

    public function destroy (OrderItems $order_Items){
        $order_Items->delete();
        return response()->json(null, 204);
    }

    public function orderItems($id){
        $order = Order::findOrFail($id);
        return OrderItemsResource::collection(OrderItems::where('order_id',$order->id)->get());
    }

    public function itemOrders($id){
        $product = Product::findOrFail($id);
        return OrderItemsResource::collection(OrderItems::where('product_id',$product->id)->get());
    }
}
