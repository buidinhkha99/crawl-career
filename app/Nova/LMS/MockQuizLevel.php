<?php

namespace App\Nova\LMS;

use App\Enums\ScopeAccountType;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Http\Requests\NovaRequest;

class MockQuizLevel extends MockQuiz
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\MockQuiz>
     */
    public static string $model = \App\Models\MockQuizLevel::class;

    public function fields(NovaRequest $request): array
    {
        return array_merge_recursive(parent::fields($request) , [
            Hidden::make('Scope', 'scope_type')->default(ScopeAccountType::LEVEL)
        ]);
    }

    public static function label(): string
    {
        return __('Mock Quiz Level');
    }
}
