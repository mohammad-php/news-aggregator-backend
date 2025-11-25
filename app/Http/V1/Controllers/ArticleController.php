<?php

declare(strict_types=1);

namespace App\Http\V1\Controllers;

use App\DTOs\News\ArticleSearchData;
use App\Services\News\ArticleService;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;


/**
 * @group Articles
 */
class ArticleController extends Controller
{

    public function __construct(
        private readonly ArticleService $articleService,
    ) {}

    /**
     * Get / Search Articles
     *
     * @unauthenticated
     *
     * @queryParam keyword string
     * Search by title or description.
     * Example: Culture
     *
     * @queryParam sources int[]
     * Filter by source IDs.
     * Example: [1,2]
     *
     * @queryParam authors string[]
     * Filter by author names.
     * Example: ["John Doe","Lisa Lerer"]
     *
     * @queryParam published_from date
     * Filter by published date (from).
     * Example: 2025-11-20
     *
     * @queryParam published_to date
     * Filter by published date (to).
     * Example: 2025-11-24
     *
     * @queryParam sort string
     * Sort Order
     * Enum: new_to_old, old_to_new, alphabetical
     *
     * @queryParam page int
     * Page number.
     * Example: 1
     *
     * @queryParam per_page int
     * Items per page.
     * Example: 20
     *
     * @param ArticleSearchData $data
     *
     * @return PaginatedDataCollection|LengthAwarePaginator|DataCollection
     */
    public function __invoke(
        ArticleSearchData $data
    ): PaginatedDataCollection|LengthAwarePaginator|DataCollection {
        return $this->articleService->getAll($data);
    }
}
