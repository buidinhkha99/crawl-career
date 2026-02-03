<?php

namespace App\Nova\LMS;

use App\Enums\ScopeAccountType;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

class QuizLevel extends Quiz
{
    protected string $examClassNova = ExamLevel::class;

    public function fields(NovaRequest $request): array
    {
        return array_merge_recursive(parent::fields($request) , [
            Hidden::make('Scope', 'scope_type')->default(ScopeAccountType::LEVEL)
        ]);
    }

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Exam>
     */
    public static string $model = \App\Models\QuizLevel::class;
}
