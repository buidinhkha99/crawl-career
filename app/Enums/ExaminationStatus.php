<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ExaminationStatus extends Enum
{
    const Pass = 'Đạt';

    const Fail = 'Không Đạt';

    const NoExam = 'Không thi';

    const NotYet = 'Chưa thi';
}
