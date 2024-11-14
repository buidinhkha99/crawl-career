<?php

namespace App\Nova\LMS;

use App\Enums\ScopeAccountType;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

class ExamLevel extends Exam
{
    public static string $model = \App\Models\ExamLevel::class;
    protected string $quizClassNova = QuizLevel::class;

    public function fieldsForCreate(NovaRequest $request): array
    {
        return array_merge_recursive(parent::fieldsForCreate($request) , [
            Hidden::make('Scope', 'scope_type')->default(ScopeAccountType::LEVEL)
        ]);
    }

    public function fields(NovaRequest $request): array
    {
        return array_merge_recursive(parent::fields($request) , [
            Hidden::make('Scope', 'scope_type')->default(ScopeAccountType::LEVEL)
        ]);
    }
}
