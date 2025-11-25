<?php

declare(strict_types=1);

namespace App\DTOs\News;

use App\Enums\SortOrder;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\DateFormat;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class ArticleSearchData extends Data
{
    /**
     * @param string|null $keyword
     * @param array|null $sources
     * @param array|null $authors
     * @param Carbon|null $publishedFrom
     * @param Carbon|null $publishedTo
     * @param SortOrder|null $sort
     */
    public function __construct(
        public readonly ?string $keyword,
        public readonly ?array $sources,
        public readonly ?array $authors,
        #[Date, DateFormat('Y-m-d')]
        public readonly ?Carbon $publishedFrom,
        #[Date, DateFormat('Y-m-d'), After('published_from')]
        public readonly ?Carbon $publishedTo,
        public readonly ?SortOrder $sort,
    ) {
    }
}
