<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FavoritesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct(
        private FavoritesService $favoritesService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return response()->json([]);
        }

        $favorites = $this->favoritesService->getFavorites($sessionId);

        return response()->json($favorites);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image_id' => 'required|exists:catalog_images,id',
            'session_id' => 'required|string|max:64',
        ]);

        $result = $this->favoritesService->toggle(
            (int) $validated['image_id'],
            $validated['session_id']
        );

        return response()->json($result);
    }

    public function destroy(int $imageId, Request $request): JsonResponse
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return response()->json(['error' => 'session_id required'], 400);
        }

        $result = $this->favoritesService->toggle($imageId, $sessionId);

        return response()->json($result);
    }
}
