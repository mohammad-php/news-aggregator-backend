<?php

declare(strict_types=1);

namespace App\Models;

use App\DTOs\ArticleData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\LaravelData\WithData;

final class Article extends Model
{
    use WithData;

    /**
     * @var string
     */
    protected string $dataClass = ArticleData::class;

    /**
     * @var string[]
     */
    protected $fillable = [
        'source_id',
        'category_id',
        'title',
        'description',
        'content',
        'author',
        'url',
        'image_url',
        'published_at',
        'dedupe_hash',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

}
