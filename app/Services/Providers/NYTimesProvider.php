<?php

declare(strict_types=1);

namespace App\Services\Providers;

use App\DTOs\ArticleData;
use App\Models\Source;
use App\Services\Contracts\NewsProviderInterface;
use App\Services\Normalizers\NYTimesNormalizer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\DataCollection;

class NYTimesProvider implements NewsProviderInterface
{
    /**
     * @var string
     */
    private string $baseUrl;
    /**
     * @var string|mixed
     */
    private string $apiKey;
    /**
     * @var array|mixed
     */
    private array $sections;

    /**
     * @param Source $source
     * @param NYTimesNormalizer $normalizer
     */
    public function __construct(
        private readonly Source $source,
        private readonly NYTimesNormalizer $normalizer,
    ) {
        $config = config('news_providers.nytimes');

        $this->baseUrl  = rtrim($config['base_url'], '/').'/';
        $this->apiKey   = $config['api_key'];
        $this->sections = $config['sections'];
    }

    /**
     * @return DataCollection
     */
    public function fetch(): DataCollection
    {
        $articles = collect();

        foreach ($this->sections as $section) {

            Log::channel('news')->info("NYTimesAPI: Fetching section [{$section}]");

            $response = $this->fetchSection($section);

            if (empty($response)) {
                Log::channel('news')->warning("NYTimesAPI: EMPTY response for {$section}");
                continue;
            }

            $results = $response['results'] ?? [];

            if (empty($results)) {
                Log::channel('news')->warning("NYTimesAPI: No results for {$section}");
                continue;
            }

            $normalized = collect($results)
                ->map(fn ($raw) => $this->normalizer->normalize($raw, $this->source->id))
                ->filter();

            $articles->push(...$normalized);
        }

        Log::channel('news')->info('NYTimesAPI: Sync finished', [
            'total' => $articles->count(),
        ]);

        return ArticleData::collect($articles->values()->all(), DataCollection::class);
    }

    /**
     * @param string $section
     *
     * @return array
     */
    private function fetchSection(string $section): array
    {
        $url = "{$this->baseUrl}{$section}.json";

        try {
            $response = Http::timeout(15)->get($url, [
                'api-key' => $this->apiKey,
            ]);
        } catch (\Throwable $e) {
            Log::channel('news')->error("NYTimesAPI: HTTP failure on {$section}", [
                'error' => $e->getMessage(),
            ]);
            return [];
        }

        if (! $response->successful()) {
            Log::channel('news')->error("NYTimesAPI: Non-200 response for {$section}", [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return [];
        }

        return $response->json() ?? [];
    }
}
