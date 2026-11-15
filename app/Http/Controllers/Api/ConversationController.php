<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Models\Listing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ConversationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = $request->user();

        $query = Conversation::query()
            ->where(function ($builder) use ($user) {
                $builder->where('buyer_id', $user->id)
                    ->orWhere('seller_id', $user->id);
            })
            ->with([
                'listing.images',
                'listing.seller',
                'buyer',
                'seller',
                'latestMessage',
            ])
            ->withCount([
                'messages as unread_count' => function ($builder) use ($user) {
                    $builder->whereNull('read_at')
                        ->where('sender_id', '!=', $user->id);
                },
            ])
            ->orderByDesc('last_message_at')
            ->orderByDesc('id');

        return ConversationResource::collection($query->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'listing_id' => ['required', 'integer', 'exists:listings,id'],
        ]);

        $listing = Listing::findOrFail($data['listing_id']);
        $buyer = $request->user();

        abort_if($listing->user_id === $buyer->id, 422, 'You cannot message yourself about your own listing.');

        $conversation = Conversation::firstOrCreate(
            [
                'listing_id' => $listing->id,
                'buyer_id' => $buyer->id,
                'seller_id' => $listing->user_id,
            ],
            ['last_message_at' => now()],
        );

        return (new ConversationResource(
            $this->loadConversation($conversation, $buyer)
        ))->response()->setStatusCode(201);
    }

    public function show(Request $request, Conversation $conversation): ConversationResource
    {
        $this->authorize('view', $conversation);

        $conversation->messages()
            ->where('sender_id', '!=', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversation->load(['messages.sender', 'meetups.proposer']);

        return new ConversationResource(
            $this->loadConversation($conversation, $request->user())
        );
    }

    public function destroy(Request $request, Conversation $conversation): JsonResponse
    {
        $this->authorize('delete', $conversation);

        $conversation->delete();

        return response()->json(null, 204);
    }

    private function loadConversation(Conversation $conversation, $user): Conversation
    {
        return $conversation
            ->load([
                'listing.images',
                'listing.seller',
                'buyer',
                'seller',
                'latestMessage',
            ])
            ->loadCount([
                'messages as unread_count' => function ($builder) use ($user) {
                    $builder->whereNull('read_at')
                        ->where('sender_id', '!=', $user->id);
                },
            ]);
    }
}
