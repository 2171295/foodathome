<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request  $request){
        if($request -> has('page')){
            return ProductResource::collection(Product::paginate(5));
        }else{
            return ProductResource::collection(Product::all());
        }
    }

    public function show(Product  $product){
        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request){
        $product = new Product();
        $product->fill($request->validated());
        $product->save();
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product){
        $product->update($request->validate());
        return new ProductResource($product);
    }

    public function destroy (Product $product){
        $product->delete();
        return response()->json(null, 204);
    }

    public function getOrders(Product $product){
        return $product->orders;
    }
}
