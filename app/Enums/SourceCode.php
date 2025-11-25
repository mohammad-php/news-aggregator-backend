<?php

declare(strict_types=1);

namespace App\Enums;

Enum SourceCode: string
{
    case NEWSAPI = 'newsapi';
    case GUARDIAN = 'guardian';
    case NYTIMES = 'nytimes';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::NEWSAPI => 'NewsAPI',
            self::GUARDIAN => 'The Guardian',
            self::NYTIMES => 'New York Times',
        };
    }
}
