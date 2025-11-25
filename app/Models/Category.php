<?php

declare(strict_types=1);

namespace App\Models;

use App\DTOs\CategoryData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\LaravelData\WithData;

final class Category extends Model
{
    use WithData;

    /**
     * @var string
     */
    protected string $dataClass = CategoryData::class;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
    ];


    /**
     * @return HasMany
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

}
