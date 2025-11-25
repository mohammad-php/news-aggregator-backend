<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\DTOs\ArticleData;

interface NewsNormalizerInterface
{
    /**
     * @param array $article
     * @param int $sourceId
     *
     * @return ArticleData|null
     */
    public function normalize(array $article, int $sourceId): ?ArticleData;
}
