<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'conversationId' => $this->conversation_id,
            'proposedBy' => $this->proposed_by,
            'proposer' => new UserResource($this->whenLoaded('proposer')),
            'location' => $this->location,
            'scheduledAt' => $this->scheduled_at?->toIso8601String(),
            'status' => $this->status,
            'note' => $this->note,
            'createdAt' => $this->created_at?->toIso8601String(),
        ];
    }
}
