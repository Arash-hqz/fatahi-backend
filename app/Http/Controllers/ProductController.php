<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $collection = ProductResource::collection($this->service->all());
        $data = $collection->toArray(request());
        if (! auth()->check()) {
            $keys = ['id', 'title', 'price', 'imageUrl', 'createdAt'];
            $data = array_map(fn($item) => array_intersect_key($item, array_flip($keys)), $data);
        }
        return response()->json($data);
    }

    public function store(CreateProductRequest $request)
    {
        $data = $request->validated();
        $image = $request->file('image') ?? null;
        $product = $this->service->create($data, $image);

        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $product = $this->service->find($id);
        if (! $product) return response()->json(['message' => 'Not found'], 404);
        return new ProductResource($product);
    }

    public function update(Request $request, $id)
    {
        $product = $this->service->find($id);
        if (! $product) return response()->json(['message' => 'Not found'], 404);
        $image = $request->file('image') ?? null;
        $updated = $this->service->update($id, $request->all(), $image);
        return new ProductResource($updated);
    }

    public function destroy($id)
    {
        if ($this->service->delete($id)) return response()->json(['message' => 'Deleted']);
        return response()->json(['message' => 'Not found'], 404);
    }
}
