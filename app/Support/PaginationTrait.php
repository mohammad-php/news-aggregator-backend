<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

trait PaginationTrait
{
    /**
     * @return int
     */
    public function getPerPage(): int
    {
        $requestPerPage = (int) request('per_page');
        $defaultPerPage = config('pagination.per_page');

        return (! empty($requestPerPage)) ? $requestPerPage : $defaultPerPage;
    }

    /**
     * @param Builder $builder
     * @param callable $transformer
     *
     * @return PaginatedDataCollection|LengthAwarePaginator|DataCollection|Collection
     */
    public function paginate(
        Builder $builder,
        callable $transformer
    ): PaginatedDataCollection|LengthAwarePaginator|DataCollection|Collection {
        return $builder
            ->paginate($this->getPerPage())
            ->through($transformer);
    }
}
