<?php

namespace App\Nova\LMS;

use App\Models\Examination;

class ExaminationLevelInReport extends ExaminationInReport
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Examination>
     */
    public static string $model = \App\Models\ExaminationLevel::class;
}
