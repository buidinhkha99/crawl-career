<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ScopeAccountType extends Enum
{
    // for account admin can see all
    const OCCUPATIONAL = 'OCCUPATIONAL';

    // for account only see career
    const CAREER = 'CAREER';

    // for account only see LEVEL
    const LEVEL = 'LEVEL';
}
