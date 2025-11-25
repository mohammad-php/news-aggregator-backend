<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\DataCollection;

class StoreArticlesAction
{
    /**
     * @param DataCollection $articles
     *
     * @return void
     */
    public function handle(DataCollection $articles): void
    {
        if (empty($articles->items())) {
            return;
        }

        $data = collect($articles->toArray())
            ->unique('dedupe_hash')
            ->values()
            ->all();

        DB::table('articles')->upsert(
            $data,
            ['dedupe_hash'],
            [
                'title',
                'description',
                'content',
                'author',
                'image_url',
                'published_at',
                'category_id',
                'updated_at',
            ]
        );
    }
}
