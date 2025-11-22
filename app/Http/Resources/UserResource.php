<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? null,
            'email' => $this->email ?? null,
            'phone' => $this->phone ?? null,
            'role' => $this->role ?? 'user',
            'active' => (bool) $this->active,
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
