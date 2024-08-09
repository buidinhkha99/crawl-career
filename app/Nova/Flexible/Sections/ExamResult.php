<?php

namespace App\Nova\Flexible\Sections;

use App\Enums\ExaminationStatus;
use App\Models\Examination;
use App\Nova\Flexible\Components\Background;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class ExamResult extends Layout
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'exam_result';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Exam Result';

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
        if (! Auth::user()) {
            return [
                'id' => $id,
                'type' => 'ExamResult',
                'background' => self::backgroundRender(
                    Arr::get($attributes, 'background_option', 'color'),
                    Arr::get($attributes, 'background', '#000000')
                ),
            ];
        }

        $exam = Auth::user()->examinations()->latest()->first();
        if (! $exam) {
            return [
                'id' => $id,
                'type' => 'ExamResult',
                'background' => self::backgroundRender(
                    Arr::get($attributes, 'background_option', 'color'),
                    Arr::get($attributes, 'background', '#000000')
                ),
            ];
        }

        return [
            'id' => $id,
            'type' => 'ExamResult',
            'background' => self::backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'exam_name' => $exam->getAttribute('exam_name'),
            'is_started_at' => $exam->getAttribute('start_time_exam'),
            'is_ended_at' => $exam->getAttribute('end_time_exam'),
            'user_info' => [
                'avatar' => $exam->getAttribute('avatar_url') ?: '/storage/default_avatar_user.png',
                'full_name' => $exam->getAttribute('name'),
                'identification_number' => $exam->getAttribute('employee_code'),
                'date_of_birth' => $exam->getAttribute('dob'),
                'coaching_team' => $exam->getAttribute('group'),
                'work_unit' => $exam->getAttribute('department'),
                'working_position' => $exam->getAttribute('position'),
            ],
            'exam_result' => [
                'right_answers' => $exam->getAttribute('correct_answer'),
                'wrong_answers' => $exam->getAttribute('wrong_answer'),
                'unanswered' => $exam->getAttribute('unanswered'),
                'score' => $exam->getAttribute('score'),
                'is_passed' => $exam->getAttribute('state') == ExaminationStatus::Pass,
            ],
            'examination' => $exam->getAttribute('examination'),
            'start_time' => $exam->getAttribute('start_time'),
            'end_time' => $exam->getAttribute('end_time'),
        ];
    }
}
