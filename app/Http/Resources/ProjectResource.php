<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'imageUrl' => $this->image ? Storage::url($this->image) : null,
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
