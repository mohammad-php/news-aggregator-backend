<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use Spatie\LaravelData\DataCollection;

interface NewsProviderInterface
{
    /**
     * @return DataCollection
     */
    public function fetch(): DataCollection;
}
