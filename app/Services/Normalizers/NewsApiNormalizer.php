<?php

declare(strict_types=1);

namespace App\Services\Normalizers;

use App\DTOs\ArticleData;
use App\Services\Contracts\NewsNormalizerInterface;
use Carbon\CarbonImmutable;

class NewsApiNormalizer implements NewsNormalizerInterface
{
    /**
     * @param array $article
     * @param int $sourceId
     *
     * @return ArticleData|null
     */
    public function normalize(array $article, int $sourceId): ?ArticleData
    {
        if (! isset($article['title'], $article['url'], $article['publishedAt'])) {
            return null;
        }

        $dedupeHash = hash(
            'sha256',
            ($article['title'] ?? '') . ($article['url'] ?? '') . ($article['publishedAt'] ?? '')
        );

        return ArticleData::from([
            'title' => $article['title'],
            'description' => $article['description'] ?? null,
            'content' => $article['content'] ?? null,
            'author' => $article['author'] ?? null,
            'url' => $article['url'],
            'image_url' => $article['urlToImage'] ?? null,
            'published_at' => CarbonImmutable::parse($article['publishedAt']),
            'source_id' => $sourceId,
            'category_id' => null,
            'dedupe_hash' => $dedupeHash,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
