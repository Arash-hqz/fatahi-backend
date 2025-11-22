<?php

namespace App\Repositories;

use App\Contracts\Repositories\ArticleRepositoryInterface;
use App\Models\Article;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function all()
    {
        return Article::all();
    }

    public function paginate($perPage = 15)
    {
        return Article::paginate($perPage);
    }

    public function find($id)
    {
        return Article::find($id);
    }

    public function create(array $data)
    {
        return Article::create($data);
    }

    public function update($id, array $data)
    {
        $article = Article::find($id);
        if (! $article) return null;
        $article->update($data);
        return $article;
    }

    public function delete($id)
    {
        $article = Article::find($id);
        if (! $article) return false;
        return $article->delete();
    }
}
