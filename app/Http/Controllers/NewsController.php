<?php

namespace App\Http\Controllers;

use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    public function __construct(
        private readonly NewsService $newsService
    ) {}

    public function searchBySymbol(Request $request): JsonResponse
    {
        $symbol = $request->get('symbol', 'BTC');
        $news = $this->newsService->getNewsBySymbol($symbol);

        return response()->json($news);
    }

    public function searchByTime(Request $request): JsonResponse
    {
        $symbol = $request->get('symbol', '');
        $fromDate = $request->get('fromDate', '');
        $toDate = $request->get('toDate', '');

        $news = $this->newsService->getNewsByTime($toDate, $fromDate, $symbol);

        return response()->json($news);
    }
}
