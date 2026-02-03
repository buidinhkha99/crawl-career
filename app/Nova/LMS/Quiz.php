<?php

namespace App\Nova\LMS;

use App\Enums\ExamStatus;
use App\Enums\QuizType;
use App\Nova\Actions\AttachGroupUserInQuiz;
use App\Nova\Actions\AttachUserInQuiz;
use App\Nova\Filters\QuizStatusFilter;
use App\Nova\Resource;
use App\Nova\User;
use Exception;
use App\Nova\Traits\HasCallbacks;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Murdercode\TinymceEditor\TinymceEditor;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Timothyasp\Badge\Badge;

class Quiz extends Resource
{
    use HasCallbacks;
    protected string $examClassNova = Exam::class;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Exam>
     */
    public static string $model = \App\Models\QuizOccupational::class;

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
            return $this->exam?->name." - $this->name";
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
         $query->withCount('questions')
            ->withCount('users')
            ->with('exam');

    }

    public static function detailQuery(NovaRequest $request, $query)
    {
        return parent::detailQuery($request, $query)->withCount('questions')->withCount('users')->with('exam');
    }

    public function fieldsForPreview(NovaRequest $request): array
    {
        return [];
    }

    /**
     * @throws Exception
     */
    public function fieldsForIndex(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')->sortable(),
            BelongsTo::make(__('Exam'), 'exam', $this->examClassNova)->filterable(),
            Number::make(__('Duration'), 'duration'),
            Number::make(__('Question Amount'), 'questions_count'),
            Number::make(__('Score Pass'), 'score_pass'),
            Number::make(__('User Count'), 'users_count')->sortable(),
            Badge::make(__('Status'), 'status')
                ->colors([
                    ExamStatus::Happening => '#22bb33',
                    ExamStatus::Upcoming => '#f0ad4e',
                    ExamStatus::Finished => '#aaaaaa',
                ]),
        ];
    }

    /**
     * @throws Exception
     */
    public function fieldsForDetail(NovaRequest $request): array
    {
        return [
            ID::make(),
            Text::make(__('Name'), 'name')->rules('required'),
            BelongsTo::make(__('Exam'), 'exam', $this->examClassNova)->rules('required')->searchable(),
            Number::make(__('Duration'), 'duration')->rules('required'),
            Number::make(__('Question Amount'), 'questions_count'),
            Number::make(__('Score Pass'), 'score_pass'),
            Number::make(__('User Count'), 'users_count'),
            Badge::make(__('Status'), 'status')
                ->colors([
                    ExamStatus::Happening => '#22bb33',
                    ExamStatus::Upcoming => '#f0ad4e',
                    ExamStatus::Finished => '#aaaaaa',
                ])->exceptOnForms(),
            SimpleRepeatable::make(__('Kit'), 'kit', [
                Select::make(__('Topics'), 'topics')
                    ->options(fn () => \App\Models\Topic::withCount('questions')->having('questions_count', '>', 0)->pluck('name', 'name'))
                    ->searchable()
                    ->rules('required'),
                Number::make(__('Amount'), 'amount')->rules('required'),
            ])->addRowLabel(__('Add topic'))
                ->fillUsing(function ($request, $model, $attribute) {
                    unset($model[$attribute]);
                })->minRows(1)->required(),

            $this->status != ExamStatus::Finished ?
                BelongsToMany::make(__('Questions'), 'questions', Question::class) :
                SimpleRepeatable::make(__('Quiz'), 'last_questions', [
                    Text::make(__('Topic'), 'topic'),
                    Text::make(__('Question Type'), 'question_type'),
                    TinymceEditor::make(__('Question'), 'content'),
                    SimpleRepeatable::make(__('Answer of the question'), 'answers', [
                        TinymceEditor::make(__('Answer of the question'), 'name'),
                        Boolean::make(__('Answer'), 'is_correct'),
                    ]),
                ]),
            BelongsToMany::make(__('Users'), 'users', User::class),
            HasMany::make(__('Tests'), 'examinations', ExaminationInQuiz::class),
        ];
    }

    public function fieldsForUpdate(NovaRequest $request): array
    {
        return collect([
            ID::make(),
            Text::make(__('Name'), 'name')->rules('required'),
            BelongsTo::make(__('Exam'), 'exam', $this->examClassNova)->rules('required'),
            Number::make(__('Duration'), 'duration')
                ->readonly()
                ->withMeta(['value' => $this->exam?->duration])
                ->dependsOn(
                    ['exam'],
                    function (Text $field, NovaRequest $request, FormData $formData) {
                        if ($formData->exam) {
                            $field->withMeta(['value' => \App\Models\Exam::Find($formData->exam)?->duration]);
                        }
                    }
                ),
            Text::make(__('Question Amount'), 'question_amount')
                ->readonly()
                ->withMeta(['value' => $this->exam?->question_amount])
                ->dependsOn(
                    ['exam'],
                    function (Text $field, NovaRequest $request, FormData $formData) {
                        if ($formData->exam) {
                            $field->withMeta(['value' => \App\Models\Exam::Find($formData->exam)?->question_amount]);
                        }
                    }
                ),

            Number::make(__('User Count'), 'users_count')->exceptOnForms(),
            Number::make(__('Question Amount'), 'questions_count')->exceptOnForms(),
            SimpleRepeatable::make(__('Kit'), 'kit', [
                Select::make(__('Topics'), 'topics')
                    ->options(fn () => \App\Models\Topic::withCount('questions')->having('questions_count', '>', 0)->pluck('name', 'name'))
                    ->searchable()
                    ->rules('required'),
                Number::make(__('Amount'), 'amount')->rules('required'),
            ])->addRowLabel(__('Add topic'))
                ->fillUsing(function ($request, $model, $attribute) {
                    unset($model[$attribute]);
                })->minRows(1)->required(),
            BelongsToMany::make(__('Questions'), 'questions', Question::class),
            BelongsToMany::make(__('Users'), 'users', User::class),
            HasMany::make(__('Tests'), 'examinations', ExaminationInQuiz::class),
        ])->filter()->toArray();
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {

        $is_exams_page = $request->viaResource() == $this->examClassNova && $request->viaResourceId;

        $belongsTo = BelongsTo::make(__('Exam'), 'exam', $this->examClassNova)->rules('required')->filterable();
        $belongsTo->setValue($request->viaResourceId);

        return collect([
            ID::make(),
            Text::make(__('Name'), 'name')->rules('required'),
            ! $is_exams_page ? $belongsTo
                : $belongsTo->readonly(),
            ! $is_exams_page ? Number::make(__('Duration'), 'duration')->hide()->readonly()
                ->dependsOn(
                    ['exam'],
                    function (Text $field, NovaRequest $request, FormData $formData) {
                        if ($formData->exam) {
                            $field->show()->default(\App\Models\Exam::find($formData->exam)?->duration);
                        }
                    }
                )
                : Number::make(__('Duration'), 'duration')
                ->readonly()
                ->default(\App\Models\Exam::find($request->viaResourceId)?->duration)
                ->dependsOn(
                    ['exam'],
                    function (Text $field, NovaRequest $request, FormData $formData) {
                        if ($formData->exam) {
                            $field->default(\App\Models\Exam::find($formData->exam)?->duration);
                        }
                    }
                ),
            ! $is_exams_page ? Text::make(__('Question Amount'), 'question_amount')->hide()->readonly()
                ->dependsOn(
                    ['exam'],
                    function (Text $field, NovaRequest $request, FormData $formData) {
                        if ($formData->exam) {
                            $field->show()->default(\App\Models\Exam::find($formData->exam)?->question_amount);
                        }
                    }
                )
                : Text::make(__('Question Amount'), 'question_amount')
                ->readonly()
                ->default(\App\Models\Exam::Find($request->viaResourceId)?->question_amount)
                ->dependsOn(
                    ['exam'],
                    function (Text $field, NovaRequest $request, FormData $formData) {
                        if ($formData->exam) {
                            $field->default(\App\Models\Exam::find($formData->exam)?->question_amount);
                        }
                    }
                ),

            Number::make(__('User Count'), 'users_count')->exceptOnForms(),
            Number::make(__('Question Amount'), 'questions_count')->exceptOnForms(),
            SimpleRepeatable::make(__('Kit'), 'kit', [
                Select::make(__('Topics'), 'topics')
                    ->options(fn () => \App\Models\Topic::withCount('questions')->having('questions_count', '>', 0)->pluck('name', 'name'))
                    ->searchable(),
                Number::make(__('Amount'), 'amount'),
            ])->addRowLabel(__('Add topic'))
                ->fillUsing(function ($request, $model, $attribute) {
                    unset($model[$attribute]);
                })->minRows(1)->required(),
            BelongsToMany::make(__('Questions'), 'questions', Question::class),
            BelongsToMany::make(__('Users'), 'users', User::class)->searchable(),
            HasMany::make(__('Tests'), 'examinations', ExaminationInQuiz::class),
        ])->filter()->toArray();
    }

    public static function beforeSave(NovaRequest $request, Model $model)
    {
        $model->setAttribute('duration', $request->get('duration'));
    }

    public static function afterSave(Request $request, $model)
    {
        $model->saveKit(collect(json_decode($request->get('kit'))));
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
            new QuizStatusFilter()
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
        $is_authorized = function (NovaRequest $request): bool {
            $request = request();

            return $request->method() === 'POST' ||
                (
                    $request->user()->can('attachAnyUser', $this->resource) &&
                    $request->user()->can('viewAny', \App\Models\User::class)
                    && ($request->viaResource != "questions" && !$request->viaResourceId && $request->viaRelationship != "quizzes")
                )
                || $request->user()->isSuperAdmin();
        };

        return [
            (new AttachUserInQuiz())->onlyOnDetail()
                ->canSee($is_authorized)
                ->canRun($is_authorized),
            (new AttachGroupUserInQuiz())->showInline()->showOnDetail()
                ->canSee($is_authorized)
                ->canRun($is_authorized),
        ];
    }

    public static function label(): string
    {
        return __('Quiz');
    }

    protected static function afterValidation(
        NovaRequest $request,
                    $validator
    ) {
        $unique = Rule::unique('quizzes')->where(function ($query) use ($request) {
            $query->where('exam_id', $request->post('exam'))
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

        $kits = collect($request->only('kit'))->mapWithKeys(fn ($value, $key) => [
            $key => collect(json_decode($value))->map(fn ($kit) => collect($kit)->toArray()),
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

    public function authorizedToReplicate(Request $request)
    {
        if ($request->viaResource == "questions" && $request->viaResourceId && $request->viaRelationship == "quizzes" ) {
            return false;
        }
        return true;
    }
}
