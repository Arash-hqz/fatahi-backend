<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    /**
     * List public products.
     * @OA\Get(
     *   path="/guest/products",
     *   tags={"Products"},
     *   summary="List products",
     *   description="Retrieve products. If user is not authenticated, only a subset of fields is returned.",
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product")))
     * )
     */
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

    /**
     * Create a new product.
     * @OA\Post(
     *   path="/admin/products",
     *   tags={"Products"},
     *   summary="Create product",
     *   description="Create a product with title, description, optional price and image.",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(required=true,
     *     @OA\JsonContent(
     *       required={"title","description"},
     *       @OA\Property(property="title", type="string", example="Phone"),
     *       @OA\Property(property="description", type="string", example="Smart phone"),
     *       @OA\Property(property="price", type="number", example=12000000),
     *     )
     *   ),
     *   @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/Product")),
     *   @OA\Response(response=403, description="Forbidden"),
     *   @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(CreateProductRequest $request)
    {
        $data = $request->validated();
        $image = $request->file('image') ?? null;
        $product = $this->service->create($data, $image);

        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    /**
     * Show product details.
     * @OA\Get(
     *   path="/guest/products/{id}",
     *   tags={"Products"},
     *   summary="Show product",
     *   description="Retrieve a product by ID. Returns 404 if not found.",
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Product", @OA\JsonContent(ref="#/components/schemas/Product")),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        $product = $this->service->find($id);
        if (! $product) return response()->json(['message' => 'Not found'], 404);
        return new ProductResource($product);
    }

    /**
     * Update an existing product.
     * @OA\Put(
     *   path="/admin/products/{id}",
     *   tags={"Products"},
     *   summary="Update product",
     *   description="Update product fields. Fields not provided remain unchanged.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(@OA\JsonContent(
     *       @OA\Property(property="title", type="string"),
     *       @OA\Property(property="description", type="string"),
     *       @OA\Property(property="price", type="number"),
     *     )
     *   ),
     *   @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/Product")),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $product = $this->service->find($id);
        if (! $product) return response()->json(['message' => 'Not found'], 404);
        $image = $request->file('image') ?? null;
        $updated = $this->service->update($id, $request->all(), $image);
        return new ProductResource($updated);
    }

    /**
     * Delete a product.
     * @OA\Delete(
     *   path="/admin/products/{id}",
     *   tags={"Products"},
     *   summary="Delete product",
     *   description="Permanently delete a product. Returns 404 if not found.",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="Deleted"),
     *   @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy($id)
    {
        if ($this->service->delete($id)) return response()->json(['message' => 'Deleted']);
        return response()->json(['message' => 'Not found'], 404);
    }
}
