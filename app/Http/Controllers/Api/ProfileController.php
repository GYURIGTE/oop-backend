<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function update(UpdateProfileRequest $request): UserResource
    {
        $user = $request->user();
        $data = $request->safe()->except(['avatar']);

        if ($request->hasFile('avatar')) {
            $this->deleteStoredFile($user->avatar_url);
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_url'] = asset('storage/'.$path);
        }

        $firstName = $data['first_name'] ?? $user->first_name;
        $lastName = $data['last_name'] ?? $user->last_name;

        if (array_key_exists('first_name', $data) || array_key_exists('last_name', $data)) {
            $data['name'] = trim($firstName.' '.$lastName);
        }

        $user->update($data);

        return new UserResource($user->fresh());
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! Hash::check($request->validated('current_password'), $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        $user->update(['password' => $request->validated('password')]);

        $currentTokenId = $request->user()->currentAccessToken()?->id;
        $user->tokens()->when($currentTokenId, fn ($query) => $query->where('id', '!=', $currentTokenId))->delete();

        return response()->json(['message' => 'Password updated.']);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate(['password' => ['required', 'string']]);

        $user = $request->user();

        if (! Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'message' => 'Password is incorrect.',
            ], 422);
        }

        foreach ($user->listings()->with('images')->get() as $listing) {
            foreach ($listing->images as $image) {
                $this->deleteStoredFile($image->url);
            }
        }

        $this->deleteStoredFile($user->avatar_url);
        $user->delete();

        return response()->json(null, 204);
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
