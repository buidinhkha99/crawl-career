<?php

namespace App\Nova\LMS;

class QuizCareer extends Quiz
{
    protected string $examClassNova = ExamCareer::class;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Exam>
     */
    public static string $model = \App\Models\QuizCareer::class;
}
