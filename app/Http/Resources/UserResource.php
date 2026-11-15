<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'fullName' => $this->full_name,
            'initials' => $this->initials,
            'email' => $this->email,
            'username' => $this->username,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'location' => $this->location,
            'avatarUrl' => $this->avatar_url,
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
