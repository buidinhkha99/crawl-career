<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PostStatus extends Enum
{
    #[Description('Draft')]
    const Draft = 0;

    #[Description('Published')]
    const Published = 1;
}
