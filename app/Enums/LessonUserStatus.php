<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LessonUserStatus extends Enum
{
    const Complete = 'Hoàn thành';
    const Incomplete = 'Chưa hoàn thành';
    const NotYet = 'Không tham gia';
}
