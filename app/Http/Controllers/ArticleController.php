<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $service;

    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $collection = ArticleResource::collection($this->service->all());
        $data = $collection->toArray(request());
        if (! auth()->check()) {
            $keys = ['id', 'title', 'slug', 'coverUrl', 'createdAt'];
            $data = array_map(fn($item) => array_intersect_key($item, array_flip($keys)), $data);
        }
        return response()->json($data);
    }

    public function store(CreateArticleRequest $request)
    {
        $data = $request->validated();
        $image = $request->file('image') ?? null;
        $article = $this->service->create($data, $image);

        return (new ArticleResource($article))->response()->setStatusCode(201);
    }

    public function show($id)
    {
        $article = $this->service->find($id);
        if (! $article) return response()->json(['message' => 'Not found'], 404);
        return new ArticleResource($article);
    }

    public function update(Request $request, $id)
    {
        $article = $this->service->find($id);
        if (! $article) return response()->json(['message' => 'Not found'], 404);
        $image = $request->file('image') ?? null;
        $updated = $this->service->update($id, $request->all(), $image);
        return new ArticleResource($updated);
    }

    public function destroy($id)
    {
        if ($this->service->delete($id)) return response()->json(['message' => 'Deleted']);
        return response()->json(['message' => 'Not found'], 404);
    }
}
