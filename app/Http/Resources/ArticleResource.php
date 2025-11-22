<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'slug' => $this->slug,
            'coverUrl' => $this->image ? $this->image : null,
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
