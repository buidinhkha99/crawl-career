<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Font extends Enum
{
    const Oswald = 'Oswald';

    const Poppins = 'Poppins';

    const Inter = 'Inter';

    const Advent_Pro = 'Advent Pro';
}
