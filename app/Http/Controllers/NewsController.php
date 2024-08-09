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

    public function searchByTime(Request $request): JsonResponse
    {
        $symbol = $request->get('symbol', '');
        $fromDate = $request->get('fromDate', '');
        $toDate = $request->get('toDate', '');

        $fromTime = empty($fromDate) ? '-inf' : strtotime($fromDate);
        $toTime = empty($toDate) ? '+inf' : strtotime($toDate);

        // Get the news IDs for the given symbol and time range
        if (!empty($symbol)) {
            $newsIds = Redis::zRevRangeByScore("news:symbol:$symbol", $toTime, $fromTime);
        } else {
            $newsIds = Redis::zRevRangeByScore("news:all", $toTime, $fromTime);
        }

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
