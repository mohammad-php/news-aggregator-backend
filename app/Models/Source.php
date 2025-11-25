<?php

declare(strict_types=1);

namespace App\Models;

use App\DTOs\SourceData;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

/**
 *
 */
final class Source extends Model
{
    use WithData;

    /**
     * @var string
     */
    protected string $dataClass = SourceData::class;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'code',
    ];

}
