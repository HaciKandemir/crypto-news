<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class NewsService
{
    public function getNewsBySymbol(string $symbol, int $pageSize = 20): array
    {
        $newsIds = Redis::zRevRange("news:symbol:$symbol", 0, $pageSize);

        return array_map([$this, 'getNewsDetails'], $newsIds);
    }

    public function getNewsByTime(string $toDate, ?string $fromDate, ?string $symbol): array
    {
        $toTime = strtotime($toDate);
        $fromTime = empty($fromDate) ? '-inf' : strtotime($fromDate);
        $key = !empty($symbol) ? "symbol:$symbol" : "all";

        $newsIds = Redis::zRevRangeByScore("news:$key", $toTime, $fromTime);

        return array_map([$this, 'getNewsDetails'], $newsIds);
    }

    public function getNewsDetails(string $newsId)
    {
        $item = Redis::hGetAll("news:$newsId");

        if (!empty($item)) {
            $item['symbols'] = isset($item['symbols']) ? json_decode($item['symbols'], true) : [];
        }

        return $item;
    }
}
