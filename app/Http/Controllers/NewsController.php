<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsBySymbolRequest;
use App\Http\Requests\NewsByTimeRequest;
use App\Http\Resources\NewsResource;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{

    public function __construct(
        private readonly NewsService $newsService
    ) {}

    public function searchBySymbol(NewsBySymbolRequest $request): JsonResponse
    {
        $news = $this->newsService->getNewsBySymbol($request->toDTO());

        return response()->json(NewsResource::collection($news));
    }

    public function searchByTime(NewsByTimeRequest $request): JsonResponse
    {
        $news = $this->newsService->getNewsByTime($request->toDTO());

        return response()->json(NewsResource::collection($news));
    }
}
