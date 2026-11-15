<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMeetupRequest;
use App\Http\Requests\UpdateMeetupRequest;
use App\Http\Resources\MeetupResource;
use App\Models\Conversation;
use App\Models\Meetup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MeetupController extends Controller
{
    public function index(Request $request, Conversation $conversation): AnonymousResourceCollection
    {
        $this->authorize('view', $conversation);

        return MeetupResource::collection(
            $conversation->meetups()->with('proposer')->get()
        );
    }

    public function store(StoreMeetupRequest $request, Conversation $conversation): JsonResponse
    {
        $this->authorize('view', $conversation);

        $meetup = $conversation->meetups()->create([
            'proposed_by' => $request->user()->id,
            'location' => $request->validated('location'),
            'scheduled_at' => $request->validated('scheduled_at'),
            'note' => $request->validated('note'),
            'status' => 'Proposed',
        ]);

        return (new MeetupResource($meetup->load('proposer')))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateMeetupRequest $request, Meetup $meetup): MeetupResource
    {
        $this->authorize('view', $meetup->conversation);

        $meetup->update($request->validated());

        return new MeetupResource($meetup->load('proposer'));
    }
}
