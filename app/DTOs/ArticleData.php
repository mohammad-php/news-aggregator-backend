<?php

declare(strict_types=1);

namespace App\DTOs;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class ArticleData extends Data
{
    /**
     * @param string $title
     * @param string|null $description
     * @param string|null $content
     * @param string|null $author
     * @param string $url
     * @param string|null $imageUrl
     * @param CarbonImmutable $publishedAt
     * @param int $sourceId
     * @param int|null $categoryId
     * @param string $dedupeHash
     * @param CarbonImmutable|null $createdAt
     * @param CarbonImmutable|null $updatedAt
     */
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly ?string $content,
        public readonly ?string $author,
        public readonly string $url,
        public readonly ?string $imageUrl,
        public readonly CarbonImmutable $publishedAt,
        public readonly int $sourceId,
        public readonly ?int $categoryId,
        public readonly string $dedupeHash,
        public readonly ?CarbonImmutable $createdAt,
        public readonly ?CarbonImmutable $updatedAt,
    ) {}
}
