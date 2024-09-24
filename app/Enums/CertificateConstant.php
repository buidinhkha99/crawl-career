<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CertificateConstant extends Enum
{
    const OCCUPATIONAL_SAFETY = 'occupational-safety';

    const ELECTRICAL_SAFETY = 'electrical-safety';
    const PAPER_SAFETY = 'paper-safety';

    const LIST_TYPE_CERTIFICATE = [
        self::OCCUPATIONAL_SAFETY,
        self::ELECTRICAL_SAFETY,
        self::PAPER_SAFETY,
    ];
}
