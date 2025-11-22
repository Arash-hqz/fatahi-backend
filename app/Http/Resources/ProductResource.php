<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'imageUrl' => $this->image ? Storage::url($this->image) : null,
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
