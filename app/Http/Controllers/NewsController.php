<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class NewsController extends Controller
{
    public function searchBySymbol(Request $request): JsonResponse
    {
        $symbol = $request->get('symbol', 'BTC');
        $pageSize = 20;

        $newsIds = Redis::zRevRange("news:symbol:$symbol", 0, $pageSize);

        // Fetch news details using the news IDs
        $news = array_map(function ($newsId) {
            $item = Redis::hGetAll("news:$newsId");
            if (isset($item['symbols'])) {
                $item['symbols'] = json_decode($item['symbols'], true);
            }
            return $item;
        }, $newsIds);

        return response()->json($news);
    }

}
