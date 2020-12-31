<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $name = $product->id . '_' . time() . '.' . $request->photo_url->getClientOriginalExtension();
        Storage::putFileAs('public/products', $request->photo_url, $name);
        $product->photo_url = $name;
        $product->save();
        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product){
        $product->update($request->validated());
        if($request->photo_url != null) {
            $name = $product->id . '_' . time() . '.' . $request->photo_url->getClientOriginalExtension();
            if($product->photo_url != null) {
                Storage::disk('public')->delete('products/'.$product->foto_url);
            }
            Storage::putFileAs('public/products', $request->photo_url, $name);

            $product->photo_url = $name;
        }

        $product->save();
        return new ProductResource($product);
    }

    public function destroy (Product $product){
        $product->delete();
        return response()->json($product, 204);
    }

    public function getOrders(Product $product){
        return $product->orders;
    }

    public function updatePhoto(Request $request, Product $product)
    {
        $request->validate([
            'photo' => 'image|mimes:jpeg,jpg,png',
        ]);

        $name = $product->id . '_' . time() . '.' . $request->photo->getClientOriginalExtension();
        if($product->photo_url != null) {
            Storage::disk('public')->delete('products/'.$product->photo_url);
        }
        Storage::putFileAs('public/products', $request->photo, $name);

        $product->photo_url = $name;
        $product->save();

        return response()->json(new ProductResource($product), 200);
    }
}
