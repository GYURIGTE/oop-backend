<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ListingResource;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MarketController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Listing::query()
            ->where('status', 'Active')
            ->with(['images', 'seller'])
            ->withCount('conversations');

        if ($search = $request->query('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }

        if ($condition = $request->query('condition')) {
            $query->where('condition', $condition);
        }

        if ($minPrice = $request->query('min_price')) {
            $query->where('price', '>=', (int) $minPrice);
        }

        if ($maxPrice = $request->query('max_price')) {
            $query->where('price', '<=', (int) $maxPrice);
        }

        match ($request->query('sort', 'recommended')) {
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'newest' => $query->latest(),
            default => $query->orderByDesc('views')->latest(),
        };

        return ListingResource::collection($query->paginate(24));
    }

    public function show(Request $request, Listing $listing): ListingResource
    {
        $isOwner = $request->user()?->id === $listing->user_id;

        abort_unless($listing->status === 'Active' || $isOwner, 404);

        if ($listing->status === 'Active') {
            $listing->increment('views');
        }

        return new ListingResource(
            $listing->load(['images', 'seller'])->loadCount('conversations')
        );
    }
}
