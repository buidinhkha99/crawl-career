<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Happening()
 * @method static static Upcoming()
 * @method static static Finished()
 */
final class ExaminationType extends Enum
{
    const Exam = 'Exam';
    const Random = 'Random';
}
