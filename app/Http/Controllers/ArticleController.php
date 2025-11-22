<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        return ArticleResource::collection(Article::all());
    }

    public function store(CreateArticleRequest $request)
    {
        $data = $request->validated();
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $imageUrl = Storage::url($path);
        }

        $article = Article::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'slug' => \Str::slug($data['title']),
            'image' => $path ?? null,
        ]);

        $resp = new ArticleResource($article);
        $arr = $resp->toArray(request());
        $arr['coverUrl'] = $imageUrl;

        return response()->json($arr, 201);
    }

    public function show($id)
    {
        $article = Article::find($id);
        if (! $article) return response()->json(['message' => 'Not found'], 404);
        $resp = new ArticleResource($article);
        $arr = $resp->toArray(request());
        $arr['coverUrl'] = $article->image ? Storage::url($article->image) : null;
        return $arr;
    }

    public function update(Request $request, $id)
    {
        $article = Article::find($id);
        if (! $article) return response()->json(['message' => 'Not found'], 404);
        $article->update($request->all());
        return new ArticleResource($article);
    }

    public function destroy($id)
    {
        $article = Article::find($id);
        if (! $article) return response()->json(['message' => 'Not found'], 404);
        $article->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
