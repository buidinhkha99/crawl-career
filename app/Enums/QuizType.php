<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Happening()
 * @method static static Upcoming()
 * @method static static Finished()
 */
final class QuizType extends Enum
{
    const Exam = 'Exam';
    const Review = 'Review';
    const Random = 'Random';
}
