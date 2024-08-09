<?php

namespace App\Nova\LMS;

use App\Enums\ExamStatus;
use App\Models\Setting;
use App\Nova\Filters\ExamStatusFilter;
use App\Nova\Resource;
use Carbon\Carbon;
use App\Nova\Traits\HasCallbacks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Murdercode\TinymceEditor\TinymceEditor;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Spatie\Period\Period;
use Spatie\Period\Precision;
use Timothyasp\Badge\Badge;

class Exam extends Resource
{
    use HasCallbacks;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Exam>
     */
    public static string $model = \App\Models\Exam::class;

    public function title()
    {
        if (\request()->get('search')) {
            return "ID: $this->id - $this->name";
        }

        return $this->name;
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];


    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->with('quiz')->orderBy('start_at', 'desc');
    }

    public function fieldsForPreview(NovaRequest $request): array
    {
        return [];
    }

    public function fieldsForIndex(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->rules('required')
                ->creationRules('unique:exams,name')
                ->updateRules('unique:exams,name,{{resourceId}}')
                ->sortable(),
            Number::make(__('Question Amount'), 'question_amount')->rules('required', 'numeric', 'min:1')->default(20),
            Number::make(__('Score Pass'), 'score_pass')
                ->rules('required', 'numeric', 'min:1', 'max:10')
                ->step(0.01)
                ->default(5),
            DateTime::make(__('Start At'), 'start_at')
                ->rules('required')
                ->creationRules('after:'.now()->subDay())
                ->default(now())->sortable(),
            DateTime::make(__('End At'), 'end_at')
                ->rules('required')
                ->default(now()->addDay())->sortable(),
            Badge::make(__('Status'), 'status')
                ->colors([
                    ExamStatus::Happening => '#22bb33',
                    ExamStatus::Upcoming => '#f0ad4e',
                    ExamStatus::Finished => '#aaaaaa',
                ])->exceptOnForms(),
        ];
    }

    public function fieldsForCreate(NovaRequest $request): array
    {
        return [
            Text::make(__('Name'), 'name')
                ->rules('required')
                ->creationRules('unique:exams,name')
                ->updateRules('unique:exams,name,{{resourceId}}'),
            DateTime::make(__('Start At'), 'start_at')
                ->rules('required')
                ->creationRules('after:'.now()->subDay())
                ->default(now()),
            DateTime::make(__('End At'), 'end_at')
                ->rules('required')
                ->default(now()->addDay()),
            Number::make(__('Question Amount'), 'question_amount')
                ->rules('required', 'numeric', 'min:1')
                ->default(20),
            Number::make(__('Score Pass'), 'score_pass')
                ->rules('required', 'numeric', 'min:0', 'max:10')
                ->step(0.01)
                ->default(5),
            Number::make(__('Duration (minute)'), 'duration')
                ->rules('required', 'numeric', 'min:1')
                ->default(20)->onlyOnForms(),
            TinymceEditor::make(__('Rule'), 'rule')
                ->fullWidth()
                ->default(Setting::get('rule'))
                ->withMeta([
                    'showForm' => false,
                ]),
            SimpleRepeatable::make(__('Quiz Kit'), 'quiz_kit', [
                Text::make(__('Name'), 'name')->rules('required'),
                Hidden::make('', 'id'),
                SimpleRepeatable::make(__('Kit'), 'kit', [
                    Select::make(__('Topics'), 'topics')
                        ->options(fn () => \App\Models\Topic::all('name')->pluck('name', 'name'))
                        ->searchable()
                        ->rules('required'),
                    Number::make(__('Amount'), 'amount')->rules('required'),
                ])->addRowLabel(__('Add topic'))->minRows(1)->required(),
            ])->addRowLabel(__('Add quiz kit'))
                ->fillUsing(function ($request, $model, $attribute) {
                    unset($model[$attribute]);
                })->minRows(1)->required(),

        ];
    }

    public function fieldsForUpdate(NovaRequest $request): array
    {
        return [
            Text::make(__('Name'), 'name')
                ->rules('required')
                ->creationRules('unique:exams,name')
                ->updateRules('unique:exams,name,{{resourceId}}'),
            DateTime::make(__('Start At'), 'start_at')
                ->rules('required')
                ->creationRules('after:'.now()->subDay())
                ->default(now()),
            DateTime::make(__('End At'), 'end_at')
                ->rules('required')
                ->default(now()->addDay()),
            Number::make(__('Question Amount'), 'question_amount')
                ->rules('required', 'numeric', 'min:0')
                ->default(20),
            Number::make(__('Score Pass'), 'score_pass')
                ->rules('required', 'numeric', 'min:0', 'max:10')
                ->step(0.01)
                ->default(5),
            Number::make(__('Duration (minute)'), 'duration')
                ->rules('required', 'numeric', 'min:1')
                ->default(20)->onlyOnForms(),
            TinymceEditor::make(__('Rule'), 'rule')
                ->fullWidth()
                ->default(Setting::get('rule'))
                ->withMeta([
                    'showForm' => false,
                ]),
            SimpleRepeatable::make(__('Quiz Kit'), 'quiz_kit', [
                Text::make(__('Name'), 'name')->rules('required'),
                Hidden::make('', 'id'),
                SimpleRepeatable::make(__('Kit'), 'kit', [
                    Select::make(__('Topics'), 'topics')
                        ->options(fn () => \App\Models\Topic::all('name')->pluck('name', 'name'))
                        ->searchable()
                        ->rules('required'),
                    Number::make(__('Amount'), 'amount')->rules('required'),
                ])->addRowLabel(__('Add topic'))->minRows(1)->required(),
            ])->addRowLabel(__('Add quiz kit'))
                ->fillUsing(function ($request, $model, $attribute) {
                    unset($model[$attribute]);
                })->minRows(1)->required(),

        ];
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make(),
            Text::make(__('Name'), 'name')
                ->rules('required')
                ->creationRules('unique:exams,name')
                ->updateRules('unique:exams,name,{{resourceId}}'),
            DateTime::make(__('Start At'), 'start_at')
                ->rules('required')
                ->creationRules('after:'.now()->subDay())
                ->default(now()),
            DateTime::make(__('End At'), 'end_at')
                ->rules('required')
                ->default(now()->addDay()),
            Badge::make(__('Status'), 'status')
                ->colors([
                    ExamStatus::Happening => '#22bb33',
                    ExamStatus::Upcoming => '#f0ad4e',
                    ExamStatus::Finished => '#aaaaaa',
                ])->exceptOnForms(),
            Number::make(__('Question Amount'), 'question_amount')->rules('required')->default(20),
            Number::make(__('Score Pass'), 'score_pass')
                ->rules('required', 'numeric', 'min:0', 'max:10')
                ->step(0.01)
                ->default(5),
            Number::make(__('Duration (minute)'), 'duration', fn() => $this->resource?->quiz?->duration)
                ->default(20)
                ->fillUsing(function ($request, $model, $attribute) {
                    unset($model[$attribute]);
                })
                ->rules('required', 'min:1'),
            SimpleRepeatable::make(__('Quiz Kit'), 'quiz_kit', [
                Text::make(__('Name'), 'name')->rules('required'),
                Hidden::make('', 'id'),
                SimpleRepeatable::make(__('Kit'), 'kit', [
                    Select::make(__('Topics'), 'topics')
                        ->options(fn () => \App\Models\Topic::all('name')->pluck('name', 'name'))
                        ->searchable()
                        ->rules('required'),
                    Number::make(__('Amount'), 'amount')->rules('required'),
                ])->addRowLabel(__('Add topic'))->minRows(1)->required(),
            ])->addRowLabel(__('Add quiz kit'))
                ->fillUsing(function ($request, $model, $attribute) {
                    unset($model[$attribute]);
                })->minRows(1)->required(),
            TinymceEditor::make(__('Rule'), 'rule')
                ->fullWidth()
                ->default(Setting::get('rule'))
                ->withMeta([
                    'showForm' => false,
                ]),
            HasMany::make(__('Quizzes'), 'quizzes', Quiz::class),
        ];
    }

    public static function afterSave(Request $request, $model)
    {
        $model->saveQuizzes(collect(json_decode($request->get('quiz_kit'))), (int) $request->get('duration'));
    }

    /**
     * Get the cards available for the request.
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     */
    public function filters(NovaRequest $request): array
    {
        return [
            new ExamStatusFilter(),
        ];
    }

    /**
     * Get the lenses available for the resource.
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }

    public static function label(): string
    {
        return __('Exams');
    }

    /**
     * @throws \Exception
     */
    protected static function afterValidation(NovaRequest $request, $validator): void
    {
        $start_at = Carbon::parse($request->post('start_at'));
        $end_at = Carbon::parse($request->post('end_at'));

        if ($start_at->gte($end_at)) {
             $validator->errors()->add('end_at',
                 __('End time must be greater than start time.'));

             return;
        }

        $period = Period::make(
            start: Carbon::parse($request->post('start_at'))->format(DATE_RFC7231),
            end: Carbon::parse($request->post('end_at'))->format(DATE_RFC7231),
            precision: Precision::SECOND(),
            format: DATE_RFC7231);

        $exams = \App\Models\Exam::whereNot('id', $request->resourceId)
            ->where('fulfilled', false)
            ->get(['id', 'start_at', 'end_at']);

        foreach ($exams as $exam) {
            $tmp_period = Period::make(
                start: Carbon::parse($exam->start_at)->format(DATE_RFC7231),
                end: Carbon::parse($exam->end_at)->format(DATE_RFC7231),
                precision: Precision::SECOND(),
                format: DATE_RFC7231);

            if ($period->overlapsWith($tmp_period)) {
                $validator->errors()->add('start_at', __('There was another exam around this time'));
                $validator->errors()->add('end_at', __('There was another exam around this time'));

                return;
            }
        }

        if ($request->post('quiz_kit') == '[]') {
            $validator->errors()->add('quiz_kit', __('The :attribute field is required.', ['attribute' => __('Quiz Kit')]));

            return;
        }

        $quiz_kit = collect($request->only('quiz_kit'))->mapWithKeys(fn ($quiz_kit, $key) => [
            $key => collect(json_decode($quiz_kit))->map(fn ($kits) => collect($kits)->mapWithKeys(fn ($kit, $key_kit) => [
                $key_kit => $key_kit !== 'kit' ? $kit : collect($kit)->map(fn ($data) => collect($data)->values()->mapWithKeys(fn ($value, $key) => [
                    $key === 0 ? 'topics' : 'amount' => $value,
                ])->toArray()
                )->toArray(),
            ])->toArray()
            )->toArray(),
        ])->toArray();

        $quizValidator = Validator::make($quiz_kit, [
            'quiz_kit.*.name' => 'required',
            'quiz_kit.*.kit.*.topics' => 'required',
            'quiz_kit.*.kit' => 'required|array',
            'quiz_kit.*.kit.*.amount' => 'required|integer|min:1',
        ], [
            'quiz_kit.*.name.required' => __('The :attribute field is required.', ['attribute' => __('Name')]),
            'quiz_kit.*.kit.required' => __('The :attribute field is required.', ['attribute' => __('Kit')]),
            'quiz_kit.*.kit.*.topics.required' => __('The :attribute field is required.', ['attribute' => __('Topic')]),
            'quiz_kit.*.kit.*.amount.required' => __('The :attribute field is required.', ['attribute' => __('Amount')]),
            'quiz_kit.*.kit.*.amount.integer' => __('The :attribute must be an integer.', ['attribute' => __('Amount')]),
            'quiz_kit.*.kit.*.amount.min' => __('The :attribute field must be at least :number.',
                [
                    'attribute' => __('Amount'),
                    'number' => 1,
                ]),
        ]);

        collect($quizValidator->errors())->each(function ($value, $key) use ($validator) {
            $validator
                ->errors()
                ->add($key, $value[0] ?? '');
        });
    }
}
