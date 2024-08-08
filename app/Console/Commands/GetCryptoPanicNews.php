<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetCryptoPanicNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-crypto-panic-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            $this->error('Failed to fetch news from CryptoPanic');
            return 1;
        }

        $results = $response->json('results', null);

        if (empty($results)) {
            $this->line('No news found');
            return 1;
        }

        $news = array_map(function ($item) {
            return [
                'title' => $item['title'],
                'published_at' => $item['published_at'],
                'currencies' => array_map(function ($currency) {
                    return $currency['code'];
                }, $item['currencies'] ?? []),
            ];
        }, $results);

    }
}
