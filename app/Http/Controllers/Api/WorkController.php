<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = 12;

        $works = Work::with('media')
            ->when($request->category, fn ($q, $v) => $q->where('category_id', $v))
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $works->getCollection()->transform(function ($work) {
            return [
                'id' => $work->id,
                'title' => $work->title,
                'description' => $work->description,
                'category_id' => $work->category_id,
                'thumb' => $work->thumb_url,
                'preview' => $work->preview_url,
                'original' => $work->getFirstMediaUrl('works'),
            ];
        });

        return response()->json($works);
    }
}
