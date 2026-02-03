<?php

namespace App\Nova\Flexible\Sections;

use App\Models\Examination;
use App\Nova\Flexible\Components\Background;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class ExamList extends Layout
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'exam_list';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Exam List';

    /**
     * Get the fields displayed by the layout.
     *
     * @throws Exception
     */
    public function fields(): array
    {
        return [
            Text::make(__('Name'), 'name')
                ->rules(['required'])
                ->sortable(),
            Text::make(__('Key'), 'key')
                ->sortable(),
            Text::make(__('Agree'), 'agree')
                ->sortable(),
            Text::make(__('Next Button'), 'next_button')->sortable(),
            Text::make(__('Previous Button'), 'prev_button')->sortable(),

            ...Background::fields(),
        ];
    }

    public function collapsedPreviewAttribute(): string
    {
        return 'name';
    }

    public function cacheable(): bool
    {
        return true;
    }

    public function render(Request $request, $id = null): array
    {
        $attributes = $this->attributes;
        $quizzes = Auth::user()?->quizzes()->whereHas('exam', function ($query) {
            $query->where('start_at', '<=', Carbon::now())->where('end_at', '>', Carbon::now());
        })->get();

        // check user do exam
        $quiz_unfinished = $quizzes->filter(function ($quiz) {
            return !$quiz?->examinations()->where('user_id', Auth::id())->exists();
        });

        return [
            'id' => $id,
            'type' => 'ExamList',
            'background' => self::backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'exams' => $quiz_unfinished->groupBy('exam.name')->map(fn($quizzes, $key) => [
                'exam_id' => $quizzes->first()->exam_id,
                'exam_name' => $key,
                'quizzes' => $quizzes->map(fn($quiz) => ['name' => $quiz->name, 'id' => $quiz->id])->values()
            ])->values(),

            'exam_name' => $quiz_unfinished?->first()?->exam?->name,
            'quizzes' => $quiz_unfinished->map(fn($quiz) => ['name' => $quiz->name, 'id' => $quiz->id])->values(),
            'config_button_one' => [
                'text' => 'TRƯỚC',
                'button_type' => 'button',
                'url' => Arr::get($attributes, 'prev_button'),
                'color_background' => '#324376',
                'detail_button_color_text' => '#ffffff',
                'icon' => [
                    'data' => '<svg width="12" height="20" viewBox="0 0 12 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 20L0 10L10 0L11.775 1.775L3.55 10L11.775 18.225L10 20Z" fill="white"/></svg>',
                ],
                'color_text' => '#ffffff',
            ],
            'config_button_two' => [
                'text' => 'TIẾP THEO',
                'button_type' => 'button',
                'url' => Arr::get($attributes, 'next_button'),
                'color_background' => '#324376',
                'detail_button_color_text' => '#ffffff',
                'icon' => [
                    'data' => '<svg width="13" height="20" viewBox="0 0 13 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.025 20L0.25 18.225L8.475 10L0.25 1.775L2.025 0L12.025 10L2.025 20Z" fill="white"/></svg>',
                ],
                'color_text' => '#ffffff',
            ],
        ];
    }
}
