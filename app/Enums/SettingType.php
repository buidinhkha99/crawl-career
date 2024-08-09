<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SettingType extends Enum
{
    const Rule = 'Rule';

    const Seo = 'Seo';

    const GeneralPage = 'GeneralPage';

    const Language = 'Language';

    const Search = 'Search';

    const ErrorPage = 'ErrorPage';

    const MediaHub = 'MediaHub';

    const QuizRandom = 'QuizRandom';
}
