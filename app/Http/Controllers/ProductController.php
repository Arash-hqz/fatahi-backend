<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    public function store(CreateProductRequest $request)
    {
        $data = $request->validated();
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $imageUrl = Storage::url($path);
        }

        $product = Product::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'] ?? 0,
            'image' => $path ?? null,
        ]);

        $resp = new ProductResource($product);
        $arr = $resp->toArray(request());
        $arr['imageUrl'] = $imageUrl;

        return response()->json($arr, 201);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (! $product) return response()->json(['message' => 'Not found'], 404);
        $resp = new ProductResource($product);
        $arr = $resp->toArray(request());
        $arr['imageUrl'] = $product->image ? Storage::url($product->image) : null;
        return $arr;
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (! $product) return response()->json(['message' => 'Not found'], 404);
        $data = $request->all();
        $product->update($data);
        return new ProductResource($product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (! $product) return response()->json(['message' => 'Not found'], 404);
        $product->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
