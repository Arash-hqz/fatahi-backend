<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResponseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'access_token' => $this['access_token'] ?? null,
            'token_type' => $this['token_type'] ?? 'bearer',
        ];
    }
}
