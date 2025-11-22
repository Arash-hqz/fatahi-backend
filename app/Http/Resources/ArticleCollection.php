<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{

    public $collects = ArticleResource::class;

    public function toArray($request)
    {
        // Ensure we return a plain array of article resources (not a Collection)
        return [
            'data' => ArticleResource::collection($this->collection)->toArray($request),
        ];
    }

}
