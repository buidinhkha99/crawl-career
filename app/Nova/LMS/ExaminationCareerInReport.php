<?php

namespace App\Nova\LMS;

use App\Models\Examination;
use App\Models\ExaminationCareer;

class ExaminationCareerInReport extends ExaminationInReport
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Examination>
     */
    public static string $model = ExaminationCareer::class;
}
