<?php

declare(strict_types=1);

namespace App\Services\Normalizers;

use App\DTOs\ArticleData;
use App\Services\Contracts\NewsNormalizerInterface;
use Carbon\CarbonImmutable;

class NYTimesNormalizer implements NewsNormalizerInterface
{
    /**
     * @param array $article
     * @param int $sourceId
     *
     * @return ArticleData|null
     */
    public function normalize(array $article, int $sourceId): ?ArticleData
    {
        if (!isset($article['title'], $article['url'], $article['published_date'])) {
            return null;
        }

        $image = null;
        if (!empty($article['multimedia']) && is_array($article['multimedia'])) {
            $image = $article['multimedia'][0]['url'] ?? null;
        }

        $dedupeHash = hash(
            'sha256',
            $article['title'].$article['url'].$article['published_date']
        );

        return ArticleData::from([
            'title'        => $article['title'],
            'description'  => $article['abstract'] ?? null,
            'content'      => null,
            'author'       => $article['byline'] ?: null,
            'url'          => $article['url'],
            'image_url'    => $image,
            'published_at' => CarbonImmutable::parse($article['published_date']),
            'source_id'    => $sourceId,
            'category_id'  => null,
            'dedupe_hash'  => $dedupeHash,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}
