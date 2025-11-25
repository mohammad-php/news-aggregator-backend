<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\Models\Source;
use App\DTOs\ArticleData;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use App\Services\Normalizers\GuardianNormalizer;
use App\Services\Contracts\NewsProviderInterface;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\DataCollection;

class GuardianProvider implements NewsProviderInterface
{
    /**
     * @var string|mixed
     */
    private string $baseUrl;
    /**
     * @var string|mixed
     */
    private string $apiKey;
    /**
     * @var string|mixed
     */
    private string $endpoint;
    /**
     * @var int
     */
    private int $page = 1;
    /**
     * @var int|mixed
     */
    private int $pageSize;
    /**
     * @var int|mixed
     */
    private int $maxPages;

    /**
     * @param Source $source
     * @param GuardianNormalizer $normalizer
     */
    public function __construct(
        private readonly Source $source,
        private readonly GuardianNormalizer $normalizer,
    ) {
        $guardianApiConfig = config('news_providers.guardian');

        $this->baseUrl   = $guardianApiConfig['base_url'];
        $this->apiKey    = $guardianApiConfig['api_key'];
        $this->endpoint  = $guardianApiConfig['endpoint'];
        $this->pageSize  = $guardianApiConfig['page_size'];
        $this->maxPages  = $guardianApiConfig['max_pages'];
    }

    /**
     * @return DataCollection
     * @throws ConnectionException
     */
    public function fetch(): DataCollection
    {
        $articles = collect();

        while (true) {

            if ($this->page > $this->maxPages) {
                Log::channel('news')->info("GuardianAPI: reached max pages limit ($this->maxPages)");
                break;
            }

            Log::channel('news')->info("GuardianAPI: fetching page {$this->page}");

            $response = $this->fetchPage($this->page, $this->pageSize);
            $results  = $response['response']['results'] ?? [];

            if (empty($results)) {
                Log::channel('news')->warning("GuardianAPI: empty page, stopping", [
                    'page' => $this->page,
                ]);
                break;
            }

            $chunk = collect($results)
                ->map(fn ($raw) => $this->normalizer->normalize($raw, $this->source->id))
                ->filter();

            $articles->push(...$chunk);

            $this->page++;
        }

        Log::channel('news')->info('GuardianAPI: sync finished', [
            'total' => $articles->count(),
        ]);

        return ArticleData::collect($articles->values()->all(), DataCollection::class);
    }


    /**
     * @throws ConnectionException
     */
    private function fetchPage(int $page, int $pageSize): array
    {
        $url = $this->baseUrl . $this->endpoint;

        $response = Http::timeout(10)
            ->acceptJson()
            ->get($url, [
                'api-key'     => $this->apiKey,
                'show-fields' => 'all',
                'page'        => $page,
                'page-size'   => $pageSize,
                'order-by'    => 'newest',
                'from-date'   => now()->subDay()->toDateString(),
            ]);

        return $response->json() ?? ['response' => ['results' => []]];
    }
}
