<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ButtonType extends Enum
{
    const Button = 'button';

    const Submit = 'submit';

    const Reset = 'reset';
}
