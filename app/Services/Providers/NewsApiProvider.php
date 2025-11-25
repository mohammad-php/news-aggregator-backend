<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\DTOs\ArticleData;
use App\Models\Source;
use App\Services\Contracts\NewsProviderInterface;
use App\Services\Normalizers\NewsApiNormalizer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\DataCollection;

class NewsApiProvider implements NewsProviderInterface
{
    /**
     * @var string|mixed
     */
    private string $baseUrl;
    /**
     * @var string|mixed
     */
    private string $endpoint;
    /**
     * @var string|mixed
     */
    private string $apiKey;
    /**
     * @var int|mixed
     */
    private int $pageSize;

    /**
     * @var int
     */
    private int $page = 1;

    /**
     * @param Source $source
     * @param NewsApiNormalizer $normalizer
     */
    public function __construct(
        private readonly Source $source,
        private readonly NewsApiNormalizer $normalizer,
    ) {
        $newsApiConfig = config('news_providers.newsapi');
        $this->baseUrl  = $newsApiConfig['base_url'];
        $this->apiKey  = $newsApiConfig['api_key'];
        $this->endpoint = $newsApiConfig['endpoint'];
        $this->pageSize = $newsApiConfig['page_size'];
    }

    /**
     * @return DataCollection
     */
    public function fetch(): DataCollection
    {
        $articles  = collect();

        while (true) {

            Log::channel('news')->info("NewsAPI: fetching page {$this->page}");

            $data = $this->fetchPage($this->page, $this->pageSize);

            if (empty($data['articles'])) {
                Log::channel('news')->warning("NewsAPI: Empty page received, stopping", [
                    'page' => $this->page,
                ]);
                break;
            }

            $chunk = collect($data['articles'])
                ->map(fn ($raw) => $this->normalizer->normalize($raw, $this->source->id))
                ->filter();

            $articles->push(...$chunk);

            if (($this->page * $this->pageSize) >= ($data['totalResults'] ?? 0)) {
                break;
            }

            $this->page++;
        }

        Log::channel('news')->info('NewsAPI: sync finished', [
            'total' => $articles->count(),
        ]);

        return ArticleData::collect($articles->values()->all(), DataCollection::class);
    }

    /**
     * @param int $page
     * @param int $pageSize
     *
     * @return array
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    private function fetchPage(int $page, int $pageSize): array
    {
        $url = $this->baseUrl . $this->endpoint;

        $response = Http::timeout(10)
            ->acceptJson()
            ->withQueryParameters([
                'apiKey'   => $this->apiKey,
                'language' => 'en',
                'pageSize' => $pageSize,
                'page'     => $page,
            ])
            ->get($url);

        if (! $response->successful()) {
            return ['articles' => [], 'totalResults' => 0];
        }

        return $response->json();
    }
}
