<?php

namespace App\Nova\LMS;

use App\Models\ExaminationCareer;

class ExaminationCareerInReport extends ExaminationInReport
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<ExaminationCareer>
     */
    public static string $model = ExaminationCareer::class;
}
