<?php

declare(strict_types=1);

namespace App\Actions;

use App\Services\Contracts\NewsProviderInterface;

class AggregateNewsAction
{
    /**
     * @param StoreArticlesAction $storeArticles
     */
    public function __construct(
        private readonly StoreArticlesAction $storeArticles,
    ) {}

    /**
     * @param NewsProviderInterface $provider
     *
     * @return void
     */
    public function handle(NewsProviderInterface $provider): void
    {
        $articles = $provider->fetch();

        $this->storeArticles->handle($articles);
    }
}
