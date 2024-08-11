<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class GetCryptoPanicNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:crypto-panic-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store CryptoPanic news in Redis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cryptoPanicNewsUrl = 'https://cryptopanic.com/api/v1/posts/';

        $response = Http::get($cryptoPanicNewsUrl, [
            'auth_token' => env('CRYPTO_PANIC_AUTH_TOKEN'),
            'kind' => 'news',
        ]);

        if ($response->status() !== 200) {
            $this->error(sprintf('%s Failed to fetch news from CryptoPanic', date('Y-m-d H:i:s')));
            return 1;
        }

        $results = $response->json('results');

        if (empty($results)) {
            $this->line(sprintf('%s Provider returned no news', date('Y-m-d H:i:s')));
            return 1;
        }

        // filter already stored news
        $newNews = array_filter($results, function ($item) {
            return !Redis::exists('news:'.$item['id']);
        });

        if (empty($newNews)) {
            $this->line(sprintf('%s No new updates', date('Y-m-d H:i:s')));
            return 1;
        }

        $this->storeNewsInRedis($newNews);

        $this->line(sprintf('%s Stored %d new news', date('Y-m-d H:i:s'), count($newNews)));
        $this->info(sprintf('%s Total news count: %d', date('Y-m-d H:i:s'), Redis::zCard('news:all')));
    }

    /**
     * @param array $newNews
     * @return void
     */
    private function  storeNewsInRedis(array $newNews): void
    {
        Redis::pipeline(function ($pipe) use ($newNews) {
            foreach ($newNews as $item) {
                $newsItem = [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'published_at' => $item['published_at'],
                    'symbols' => json_encode(array_map(function ($currency) {
                        return $currency['code'];
                    }, $item['currencies'] ?? [])),
                ];

                $newsId = $item['id'];
                $symbols = $item['currencies'] ?? [];
                $time = strtotime($item['published_at']);

                // store news item
                $pipe->hMset('news:'.$newsId, $newsItem);
                // store sorted newsId by time
                $pipe->zAdd('news:all', 'NX', $time, $newsId);
                // store newsId grouped by symbol, sorted by time
                foreach ($symbols as $symbol) {
                    $pipe->zAdd('news:symbol:'.$symbol['code'], 'NX', $time, $newsId);
                }
            }
        });
    }
}
