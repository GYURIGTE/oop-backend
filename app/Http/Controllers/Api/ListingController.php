<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use App\Http\Resources\ListingResource;
use App\Models\Listing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = $request->user()
            ->listings()
            ->with(['images', 'seller'])
            ->withCount('conversations')
            ->latest();

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->query('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        return ListingResource::collection($query->paginate(20));
    }

    public function store(StoreListingRequest $request): JsonResponse
    {
        $data = $request->validated();

        $listing = $request->user()->listings()->create([
            'name' => $data['name'],
            'category' => $data['category'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'condition' => $data['condition'],
            'location' => $data['location'] ?? null,
            'status' => $data['status'] ?? 'Active',
        ]);

        $this->storeImages($listing, $request->file('images', []));

        return (new ListingResource(
            $listing->load(['images', 'seller'])->loadCount('conversations')
        ))->response()->setStatusCode(201);
    }

    public function show(Request $request, Listing $listing): ListingResource
    {
        $this->authorize('view', $listing);

        return new ListingResource(
            $listing->load(['images', 'seller'])->loadCount('conversations')
        );
    }

    public function update(UpdateListingRequest $request, Listing $listing): ListingResource
    {
        $this->authorize('update', $listing);

        $listing->update($request->safe()->except(['images', 'removed_image_ids']));

        if ($removedIds = $request->validated('removed_image_ids')) {
            $images = $listing->images()->whereIn('id', $removedIds)->get();

            foreach ($images as $image) {
                $this->deleteStoredFile($image->url);
                $image->delete();
            }
        }

        $this->storeImages($listing, $request->file('images', []));

        return new ListingResource(
            $listing->load(['images', 'seller'])->loadCount('conversations')
        );
    }

    public function destroy(Listing $listing): JsonResponse
    {
        $this->authorize('delete', $listing);

        foreach ($listing->images as $image) {
            $this->deleteStoredFile($image->url);
        }

        $listing->delete();

        return response()->json(null, 204);
    }

    /**
     * @param  array<int, \Illuminate\Http\UploadedFile>  $files
     */
    private function storeImages(Listing $listing, array $files): void
    {
        $position = (int) $listing->images()->max('position');

        foreach ($files as $file) {
            $path = $file->store('listings', 'public');

            $listing->images()->create([
                'url' => asset('storage/'.$path),
                'position' => ++$position,
            ]);
        }
    }

    private function deleteStoredFile(?string $url): void
    {
        if (! $url) {
            return;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: $url;

        if (Str::startsWith($path, '/storage/')) {
            Storage::disk('public')->delete(Str::after($path, '/storage/'));
        }
    }
}
