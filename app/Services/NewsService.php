<?php

namespace App\Services;

use App\Dto\NewsBySymbolDto;
use App\Dto\NewsByTimeDto;
use Illuminate\Support\Facades\Redis;

class NewsService
{
    public function getNewsBySymbol(NewsBySymbolDto $bySymbolDto): array
    {
        $pageSize = 20;
        $newsIds = Redis::zRevRange("news:symbol:$bySymbolDto->symbol", 0, $pageSize);

        return $this->getMultipleNewsDetails($newsIds);
    }

    public function getNewsByTime(NewsByTimeDto $byTimeDto): array
    {
        $toTime = $byTimeDto->toDate->getTimestamp();
        $fromTime = empty($byTimeDto->fromDate) ? '-inf' : $byTimeDto->fromDate->getTimestamp();
        $key = !empty($byTimeDto->symbol) ? "symbol:$byTimeDto->symbol" : "all";

        $newsIds = Redis::zRevRangeByScore("news:$key", $toTime, $fromTime);

        return $this->getMultipleNewsDetails($newsIds);
    }

    public function getMultipleNewsDetails(array $newsIds)
    {
        return Redis::pipeline(function ($pipe) use ($newsIds) {
            foreach ($newsIds as $newsId) {
                $pipe->hGetAll("news:$newsId");
            }
        });
    }

    public function getNewsDetails(string $newsId)
    {
        return Redis::hGetAll("news:$newsId");
    }
}
