<?php

declare(strict_types=1);

namespace App\Builders\News;

use App\DTOs\News\ArticleSearchData;
use App\Enums\SortOrder;
use Illuminate\Database\Eloquent\Builder;

final readonly class ArticleSearchQueryBuilder
{
    /**
     * @param Builder $query
     * @param ArticleSearchData $data
     */
    public function __construct(
        private Builder           $query,
        private ArticleSearchData $data,
    ) {}

    /**
     * @return Builder
     */
    public function execute(): Builder
    {
        return $this
            ->filterKeyword()
            ->filterSources()
            ->filterAuthors()
            ->filterPublishedRange()
            ->applySort()
            ->query;
    }

    /**
     * @return self
     */
    private function filterKeyword(): self
    {
        $this->query->when(
            $this->data->keyword,
            fn (Builder $q, string $keyword) =>
            $q->where(function (Builder $q) use ($keyword) {
                $q->where('title', 'ILIKE', "%{$keyword}%")
                    ->orWhere('description', 'ILIKE', "%{$keyword}%")
                    ->orWhere('content', 'ILIKE', "%{$keyword}%");
            })
        );

        return $this;
    }

    /**
     * @return self
     */
    private function filterSources(): self
    {
        $this->query->when(
            ! empty($this->data->sources),
            fn (Builder $q) => $q->whereIn('source_id', $this->data->sources)
        );

        return $this;
    }

    /**
     * @return self
     */
    private function filterAuthors(): self
    {
        $this->query->when(
            ! empty($this->data->authors),
            function (Builder $q) {
                foreach ($this->data->authors as $author) {
                    if (!empty($author)) {
                        $q->where('author', 'ILIKE', "%{$author}%");
                    }
                }
            }
        );

        return $this;
    }

    /**
     * @return self
     */
    private function filterPublishedRange(): self
    {
        $this->query->when(
            $this->data->publishedFrom,
            fn (Builder $q, string $from) => $q->whereDate('published_at', '>=', $from)
        );

        $this->query->when(
            $this->data->publishedTo,
            fn (Builder $q, string $to) => $q->whereDate('published_at', '<=', $to)
        );

        return $this;
    }

    /**
     * @return self
     */
    private function applySort(): self
    {
        match ($this->data->sort) {
            SortOrder::NEW_TO_OLD => $this->query->orderBy('published_at', 'desc'),
            SortOrder::OLD_TO_NEW => $this->query->orderBy('published_at', 'asc'),
            SortOrder::ALPHABETICAL => $this->query->orderBy('title', 'asc'),
            default => $this->query->orderBy('published_at', 'desc'),
        };

        return $this;
    }
}
