<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Order as OrderResource;
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
}
