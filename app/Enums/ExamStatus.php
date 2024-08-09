<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static Happening()
 * @method static static Upcoming()
 * @method static static Finished()
 */
final class ExamStatus extends Enum
{
    const Happening = 'Đang diễn ra';

    const Upcoming = 'Sắp diễn ra';

    const Finished = 'Đã kết thúc';
}
