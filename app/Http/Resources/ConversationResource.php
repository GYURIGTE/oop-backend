<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $other = $user ? $this->otherUserFor($user) : null;

        return [
            'id' => $this->id,
            'listing' => new ListingResource($this->whenLoaded('listing')),
            'buyer' => new UserResource($this->whenLoaded('buyer')),
            'seller' => new UserResource($this->whenLoaded('seller')),
            'otherUser' => $other ? new UserResource($other) : null,
            'lastMessage' => $this->latestMessage
                ? new MessageResource($this->latestMessage)
                : null,
            'messages' => MessageResource::collection($this->whenLoaded('messages')),
            'unreadCount' => (int) ($this->unread_count ?? 0),
            'lastMessageAt' => $this->last_message_at?->toIso8601String(),
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
