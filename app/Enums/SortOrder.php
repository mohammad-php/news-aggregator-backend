<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * SortOrder Enum
 */
enum SortOrder: string
{
    case NEW_TO_OLD = 'new_to_old';
    case OLD_TO_NEW = 'old_to_new';
    case ALPHABETICAL = 'alphabetical';

}
