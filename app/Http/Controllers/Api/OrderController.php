<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\OrderProducts as OrderProductsResource;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('page')) {
            return OrderResource::collection(Order::paginate(5));
        } else {
            return OrderResource::collection(Order::all());
        }
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function store(StoreOrderRequest $request){
        $order = new Order();
        $order->fill($request->validated());
        $order->save();
        return new OrderResource($order);
    }

    public function update(UpdateOrderRequest $request, Order $order){
        $order->update($request->validate());
        return new OrderResource($order);
    }

    public function destroy (Order $order){
        $order->delete();
        return response()->json(null, 204);
    }

    public function getProducts(Order $order){
        return OrderProductsResource::collection($order->products);
    }
}
