<?php

declare(strict_types=1);

namespace App\Services\News;

use App\Builders\News\ArticleSearchQueryBuilder;
use App\DTOs\ArticleData;
use App\DTOs\News\ArticleSearchData;
use App\Models\Article;
use App\Support\PaginationTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class ArticleService
{
    use PaginationTrait;

    /**
     * @param ArticleSearchData $data
     *
     * @return PaginatedDataCollection|LengthAwarePaginator|DataCollection
     */
    public function getAll(
        ArticleSearchData $data
    ): PaginatedDataCollection|LengthAwarePaginator|DataCollection {

        $query = Article::query()
            ->with('source');

        $builder = (new ArticleSearchQueryBuilder($query, $data))->execute();

        return $this->paginate(
            $builder,
            fn ($article) => ArticleData::from($article)
        );
    }
}
