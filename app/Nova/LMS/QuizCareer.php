<?php

namespace App\Nova\LMS;

use App\Enums\ScopeAccountType;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

class QuizCareer extends Quiz
{
    protected string $examClassNova = ExamCareer::class;

    public function fields(NovaRequest $request): array
    {
        return array_merge_recursive(parent::fields($request) , [
            Hidden::make('Scope', 'scope_type')->default(ScopeAccountType::CAREER)
        ]);
    }

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Exam>
     */
    public static string $model = \App\Models\QuizCareer::class;
}
