<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'callback_url' => $this->callback_url,
            'state_code' => $this->state_code,
            'oath_enabled' => $this->oauth_enabled,
            'base_url' => $this->base_url,
            'secret' => $this->secret,
            'token' => $this->access_token,
        ];
    }
}
