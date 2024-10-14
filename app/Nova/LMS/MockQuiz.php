<?php

namespace App\Nova\LMS;

use App\Enums\QuizType;
use App\Nova\Resource;
use App\Nova\Traits\HasCallbacks;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Outl1ne\NovaSortable\Traits\HasSortableRows;

class MockQuiz extends Resource
{
    use HasCallbacks, HasSortableRows;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\MockQuiz>
     */
    public static string $model = \App\Models\MockQuiz::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public function title()
    {
        if (\request()->get('search')) {
            return "ID: $this->id - $this->name";
        }

        if (\request()->get('viaRelationship')) {
            return $this->exam?->name . " - $this->name";
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
    ];

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make(),
            BelongsTo::make(__('Group'), 'group', QuizGroup::class),
            Number::make(__('Order'), 'sort_order')->readonly()->exceptOnForms(),
            Number::make(__('Duration (minute)'), 'duration')->rules('required'),
            Number::make(__('Score Pass'), 'score_pass_quiz')
                ->rules('required', 'numeric', 'min:0', 'max:10')
                ->step(0.01),
            Number::make(__('Question Amount'), 'question_amount_quiz')->rules('required', 'integer', 'min:1',),
            SimpleRepeatable::make(__('Kit'), 'kit', [
                Select::make(__('Topics'), 'topics')
                    ->options(fn() => \App\Models\Topic::pluck('name', 'name'))
                    ->searchable(),
                Number::make(__('Amount'), 'amount'),
            ])->addRowLabel(__('Add topic'))
                ->fillUsing(function ($request, $model, $attribute) {
                    unset($model[$attribute]);
                })->minRows(1)->required(),
            BelongsToMany::make(__('Questions'), 'questions', Question::class),
            Hidden::make('Type', 'type')->default(QuizType::Review)
        ];
    }

    public static function beforeSave(NovaRequest $request, Model $model)
    {
        $model->setAttribute('duration', $request->get('duration'));
    }

    public static function afterSave(Request $request, $model)
    {
        $model->saveKit(collect(json_decode($request->get('kit'))), 'quiz_review', $request->get('question_amount_quiz'));
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
        return [];
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
        return __('Mock Quiz');
    }

    protected static function afterValidation(
        NovaRequest $request,
                    $validator
    )
    {
        $unique = Rule::unique('quizzes')->where(function ($query) use ($request) {
            $query->where('type', QuizType::Review)
                ->whereNot('id', $request->route('resourceId'))
                ->where('name', $request->post('name'));
        });

        $uniqueValidator = Validator::make($request->only('name'), [
            'name' => [$unique],
        ]);
        if ($uniqueValidator->fails()) {
            $validator
                ->errors()
                ->add(
                    'name',
                    __('The :field field has already been taken.', [
                        'field' => __('Name'),
                    ])
                );
        }

        if ($request->post('kit') === '[]') {
            return $validator->errors()->add('kit', __('The :attribute field is required.', ['attribute' => __('Kit')]));
        }

        $kits = collect($request->only('kit'))->mapWithKeys(fn($value, $key) => [
            $key => collect(json_decode($value))->map(fn($kit) => collect($kit)->toArray()),
        ])->toArray();

        $kitValidator = Validator::make($kits, [
            'kit.*.topics' => 'required',
            'kit.*.amount' => 'required|integer|min:1',
        ], [
            'kit.*.topics.required' => __('The :attribute field is required.', ['attribute' => __('Topic')]),
            'kit.*.amount.required' => __('The :attribute field is required.', ['attribute' => __('Amount')]),
            'kit.*.amount.integer' => __('The :attribute must be an integer.', ['attribute' => __('Amount')]),
            'kit.*.amount.min' => __('The :attribute field must be at least :number.',
                [
                    'attribute' => __('Amount'),
                    'number' => 1,
                ]),
        ]);

        collect($kitValidator->errors())->each(function ($value, $key) use ($validator) {
            $validator
                ->errors()
                ->add($key, $value[0] ?? '');
        });
    }

}
