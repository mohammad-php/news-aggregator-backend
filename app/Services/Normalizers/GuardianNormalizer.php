<?php

declare(strict_types=1);

namespace App\Services\Normalizers;

use App\DTOs\ArticleData;
use App\Services\Contracts\NewsNormalizerInterface;
use Carbon\CarbonImmutable;

class GuardianNormalizer implements NewsNormalizerInterface
{
    /**
     * @param array $article
     * @param int $sourceId
     *
     * @return ArticleData|null
     */
    public function normalize(array $article, int $sourceId): ?ArticleData
    {
        if (! isset($article['webTitle'], $article['webUrl'], $article['webPublicationDate'])) {
            return null;
        }

        $fields = $article['fields'] ?? [];

        $dedupeHash = hash(
            'sha256',
            $article['webTitle'] . $article['webUrl'] . $article['webPublicationDate']
        );

        return ArticleData::from([
            'title'        => $article['webTitle'],
            'description'  => $fields['trailText'] ?? null,
            'content'      => $fields['body'] ?? null,
            'author'       => $fields['byline'] ?? null,
            'url'          => $article['webUrl'],
            'image_url'    => $fields['thumbnail'] ?? null,
            'published_at' => CarbonImmutable::parse($article['webPublicationDate']),
            'source_id'    => $sourceId,
            'category_id'  => null,
            'dedupe_hash'  => $dedupeHash,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
