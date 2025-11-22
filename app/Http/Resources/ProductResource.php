<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'name' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $this->image,
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
