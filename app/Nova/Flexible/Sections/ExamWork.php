<?php

namespace App\Nova\Flexible\Sections;

use App\Nova\Flexible\Components\Background;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class ExamWork extends Layout
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'exam_work';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Exam Work';

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
            Text::make(__('Result Link'), 'result_link')->rules(['required']),
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

    public function render(Request $request, $id = null): mixed
    {
        $attributes = $this->attributes;

        $quiz = Auth::user()?->quizzes()->whereHas('exam', function ($query) {
            $query->where('start_at', '<=', Carbon::now())->where('end_at', '>', Carbon::now());
        })->where('quizzes.id', $request->route()->parameter('id'))->first();

        if (! $quiz || $quiz->examinations()->where('user_id', Auth::id())->exists()) {
            return self::initialize_data($attributes, $id);
        }

        return [
            ...self::initialize_data($attributes, $id),
            'duration' => $quiz->getAttribute('duration'),
            'exam_id' => $quiz->getAttribute('exam')?->getAttribute('id'),
            'quiz_id' => $quiz->getAttribute('id'),
            'question_list' => $quiz->getAttribute('questions')?->map(fn ($question) => [
                'question_id' => $question->getAttribute('id'),
                'question_type' => $question->getAttribute('question_type')?->getAttribute('type'),
                'question_content' => $question->getAttribute('name'),
                'answers' => $question->getAttribute('options')->map(fn ($answer) => [
                    'id' => $answer->getAttribute('id'),
                    'data' => $answer->getAttribute('name'),
                ]),
            ]),
        ];
    }

    protected function initialize_data($attributes, $id): array
    {
        return [
            'id' => $id,
            'type' => 'ExamWork',
            'background' => self::backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'result_link' => Arr::get($attributes, 'result_link'),
            'config_button_next' => [
                'text' => 'TIẾP THEO',
                'button_type' => 'button',
                'url' => null,
                'color_background' => '#324376',
                'detail_button_color_text' => '#ffffff',
                'icon' => [
                    'data' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="white">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>',
                ],
                'color_text' => '#ffffff',
            ],
            'config_button_prev' => [
                'text' => 'TRƯỚC',
                'button_type' => 'button',
                'url' => null,
                'color_background' => '#324376',
                'detail_button_color_text' => '#ffffff',
                'icon' => [
                    'data' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="white">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>',
                ],
                'color_text' => '#ffffff',
            ],
            'config_button_submit' => [
                'text' => 'NỘP BÀI',
                'button_type' => 'button',
                'url' => null,
                'color_background' => '#324376',
                'detail_button_color_text' => '#ffffff',
                'icon' => [],
                'color_text' => '#ffffff',
            ],
        ];
    }
}
