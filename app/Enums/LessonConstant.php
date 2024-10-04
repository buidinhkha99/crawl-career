<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LessonConstant extends Enum
{
    const LESSON_TYPE_NORMAL_TEXT = 'NORMAL-TEXT';

    const LESSON_TYPE_LINK_DRIVER = 'LINK-DRIVER';

    const LIST_LESSON_TYPE = [
        self::LESSON_TYPE_NORMAL_TEXT,
        self::LESSON_TYPE_LINK_DRIVER,
    ];
}
