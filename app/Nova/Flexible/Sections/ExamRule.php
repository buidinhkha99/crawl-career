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

class ExamRule extends Layout
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'exam_rule';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Exam Rule';

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
        $quiz = Auth::user()?->quizzes()->whereHas('exam', function ($query) {
            $query->where('start_at', '<=', Carbon::now())->where('end_at', '>', Carbon::now());
        })->where('quizzes.id', $request->route()->parameter('id'))->first();
        $rule = $quiz?->getAttribute('exam')?->getAttribute('rule');

        return [
            'id' => $id,
            'type' => 'ExamRules',
            'background' => self::backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'quiz_id' => $request->route()->parameter('id'),
            'agree' => Arr::get($attributes, 'agree'),
            'description' => $rule === null ? null : $rule,
            'is_completed' => $quiz?->examinations()->where('user_id', Auth::id())->exists(),
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
                'text' => 'BẮT ĐẦU THI',
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
