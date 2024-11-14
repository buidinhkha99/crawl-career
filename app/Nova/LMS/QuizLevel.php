<?php

namespace App\Nova\LMS;

class QuizLevel extends Quiz
{
    protected string $examClassNova = ExamLevel::class;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Exam>
     */
    public static string $model = \App\Models\QuizLevel::class;
}
