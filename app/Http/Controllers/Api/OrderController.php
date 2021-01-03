<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\Order as OrderResource;
use App\Http\Resources\OrderProducts as OrderProductsResource;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
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

    public function openOrders(){
        return OrderResource::collection(Order::where('status','!=','D')->where('status','!=','C')->orderBy('current_status_at','desc')->get());
    }
    public function ordersReady(){
        return OrderResource::collection(Order::where('status','R')->orderBy('current_status_at')->get());
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function store(StoreOrderRequest $request){
        $order = new Order();
        $mytime = Carbon::now();
        $order->date = $mytime->toDateTimeString();
        $order->current_status_at = $mytime->toDateTimeString();
        $order->opened_at = $mytime->toDateTimeString();
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

    public function getProducts(Order $order)
    {
        return OrderProductsResource::collection($order->products);
    }

    public function preparedBy(User $user)
    {
        $order = Order::where('status', 'P')->where('prepared_by', $user->id)->first();
        if ($order != null)
            return new OrderResource($order);
        return null;
    }

    public function deliveredBy(User $user)
    {
        $order = Order::where('status', 'T')->where('delivered_by', $user->id)->first();
        if ($order != null)
            return new OrderResource($order);
        return null;
    }

    public function defineCooker(Request $request, Order $order){
        $mytime = Carbon::now();
        $cooker = $request->cooker;
        $order->prepared_by = $cooker['id'];
        $order->current_status_at = $mytime->toDateTimeString();
        $order->status = 'P';
        $order->save();
    }
    public function defineDeliveryMan(Request $request, Order $order){
        $mytime = Carbon::now();
        $delivery_man = $request->delivery_man;
        $order->delivered_by = $delivery_man['id'];
        $order->current_status_at = $mytime->toDateTimeString();
        $order->status = 'T';
        $order->save();
    }

    public function holdingOrders(){
        $order = Order::where('status','H')->orderBy('current_status_at')->first();
        if ($order != null)
            return new OrderResource($order);
        return null;
    }

    public function orderCooked(Request $request,Order $order){
        $preparation_time = $request->preparation_time;
        $mytime = Carbon::now();
        $order->current_status_at = $mytime->toDateTimeString();
        $order->preparation_time = $preparation_time;
        $order->status = 'R';
        $order->save();
    }
}
