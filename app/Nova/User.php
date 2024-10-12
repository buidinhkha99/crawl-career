<?php

namespace App\Nova;

use App\Enums\CertificateConstant;
use App\Enums\LessonUserStatus;
use App\Enums\UserGender;
use App\Nova\Actions\AccountVerification;
use App\Nova\Actions\CancelVerification;
use App\Nova\Actions\DownloadExcelTemplate;
use App\Nova\Actions\ImportUser;
use App\Nova\Actions\ResetPassword;
use App\Nova\Filters\GroupNameFilter;
use App\Nova\Filters\UserStatusFilter;
use App\Nova\LMS\Certificates\ElectricalCertificate;
use App\Nova\LMS\Certificates\OccupationalCertificate;
use App\Nova\LMS\Certificates\PaperCertificate;
use App\Nova\LMS\ExaminationInUser;
use App\Nova\LMS\Lesson;
use App\Nova\LMS\MockQuiz;
use App\Nova\LMS\Question;
use App\Nova\LMS\Quiz;
use App\Rules\DoesntContainEmojis;
use App\Rules\FullnameRule;
use Carbon\Carbon;
use App\Nova\Traits\HasCallbacks;
use Illuminate\Database\Eloquent\Model;
use Inspheric\Fields\Url;
use Laravel\Nova\Badge;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\MultiselectField\Multiselect;
use Outl1ne\NovaMediaHub\Models\Media;
use Outl1ne\NovaMediaHub\Nova\Fields\MediaHubField;

class User extends Resource
{
    use HasCallbacks;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\User::class;

    public function title(): string
    {
        return $this->employee_code ? "$this->employee_code - $this->name" : "$this->name";
    }

    public static function label(): string
    {
        return __('User');
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name','employee_code', 'username',
    ];

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->manageRoles()->withCount('roles')->with('group')->with('groups');
    }

    public function fieldsForPreview(NovaRequest $request): array
    {
        return [];
    }

    public function fieldsForIndex(NovaRequest $request): array
    {
        return [
            Text::make(__('Name User'), 'name')
                ->sortable()
                ->rules('required', 'max:50'),
            Text::make(__('Employee Code'), 'employee_code')
                ->sortable(),
            Multiselect::make(__('Gender'), 'gender')
                ->options(UserGender::asArray())
                ->singleSelect()
                ->rules('required'),
            Date::make(__('Date Of Birth'), 'dob')
                ->max(Carbon::now())
                ->displayUsing(fn ($value) => $value ? $value->format('d/m/Y') : null)
                ->rules('required'),
            Text::make(__('CCCD/CMND'), 'username'),
            Boolean::make(__('Status'), 'status')->sortable(),
            \Laravel\Nova\Fields\Badge::make(__('Status Lesson'), 'state', function () use($request) {
                $lesson = $this->lessons()->where('lesson_id',$request->query('viaResourceId'))->first();
                if ($lesson->pivot->is_complete == true) return LessonUserStatus::Complete;

                if ($lesson->pivot->complete_theory || $lesson->histories()->where('user_id', $this->id)->exists())  return LessonUserStatus::Incomplete;

                return LessonUserStatus::NotYet;
            })->map([
                LessonUserStatus::Complete => 'success',
                LessonUserStatus::Incomplete => 'warning',
                LessonUserStatus::NotYet => 'danger',
            ])->labels([
                LessonUserStatus::Complete => __('Complete'),
                LessonUserStatus::Incomplete => __('Incomplete'),
                LessonUserStatus::NotYet => __('Not engaged'),
            ])->canSee(function ($request) {
                return $request->query('viaResourceId') && $request->query('viaResource') == 'lessons';
            }),
        ];
    }

    /**
     * Get the fields displayed by the resource.
     */
    public function fields(NovaRequest $request): array
    {
        return [
            Multiselect::make(__('Group User'), 'group_id')
                ->options(\App\Models\UserGroup::pluck('name', 'id'))
                ->withMeta(['value' => $this->groups?->pluck('id')])
                ->onlyOnForms(),
            Text::make(__('Employee Code'), 'employee_code')
                ->rules(['required', 'max:50', new DoesntContainEmojis()])
                ->creationRules('unique:users,employee_code')
                ->updateRules('unique:users,employee_code,{{resourceId}}')
                ->sortable(),
            Text::make(__('Group User'), 'group', function () {
                if (empty($this->groups)) {
                    return null;
                }

                return $this->groups->map(function($group, $index) {
                    if ($index === $this->groups->count() - 1) {
                        return '<a class="link-default" href="'.sprintf(
                                '%s/resources/%s/%d', config('nova.path'),
                                UserGroup::uriKey(), $group?->id).'">'.$group?->name.'</a>';
                    }

                    return '<a class="link-default" href="'.sprintf(
                            '%s/resources/%s/%d', config('nova.path'),
                            UserGroup::uriKey(), $group?->id).'">'.$group?->name.' , </a>';
                }
                )->implode(' ');
            })->asHtml()->onlyOnDetail(),
            Text::make(__('Name User'), 'name')
                ->sortable()
                ->rules(['required', 'max:50', new FullnameRule()]),
            MediaHubField::make(__('Avatar'), 'avatar')
                ->defaultCollection('users')
                ->rules(fn ($request) => [
                    function ($attribute, $value, $fail) {
                        $mime_types = collect(['image/jpeg', 'image/png']);
                        $media = Media::select('id', 'mime_type')->find($value);
                        if ($media && ! $mime_types->contains($media?->mime_type)) {
                            return $fail(__('The :attribute does not match the format :format.', [
                                'attribute' => $attribute,
                                'format' => $mime_types->join(', '),
                            ]));
                        }
                    },
                ]),
            Hidden::make(__('Avatar'), 'avatar_url')->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
                $model->{$attribute} = Media::find($request->input('avatar'))?->url;
            }),
            Gravatar::make(__('Avatar'))
                ->maxWidth(50)
                ->canSee(fn () => false),
            Date::make(__('Date Of Birth'), 'dob')
                ->max(Carbon::now())
                ->displayUsing(fn ($value) => $value ? $value->format('d/m/Y') : null)
                ->rules('required'),
            Text::make(__('Phone Number'), 'phone')
                ->rules(['nullable', 'regex:/((\+|)84|0[3|5|7|8|9])+([0-9]{8,9})\b/']),
            Multiselect::make(__('Gender'), 'gender')
                ->options(UserGender::asArray())
                ->nullable()
                ->singleSelect(),
            Text::make(__('CCCD/CMND'), 'username')
                ->sortable()
                ->rules('nullable', 'numeric', 'digits_between:9,12')
                ->hideFromIndex()
                ->creationRules('unique:users,username')
                ->updateRules('unique:users,username,{{resourceId}}'),
            Text::make(__('Email'), 'email')
                ->sortable()
                ->rules('email', 'max:50', 'nullable'),

            Text::make(__('Position '), 'position')
                ->hideFromIndex(),
            Text::make(__('Department'), 'department')
                ->hideFromIndex(),
            Text::make(__('Factory'), 'factory_name')
                ->hideFromIndex(),
            Text::make(__('Username'), 'username_grender')
                ->displayUsing(fn () => $this->employee_code)
                ->onlyOnDetail(),
            Password::make(__('Password'), 'passwords')
                ->default($this->dob?->format('dmY'))
                ->onlyOnDetail(),

            Country::make(__('Country'), 'country')->canSee(fn () => false),

            Text::make(__('Company'), 'company')
                ->hideFromIndex()->canSee(fn () => false),

            Textarea::make(__('Biography'), 'biography')
                ->hideFromIndex()
                ->canSee(fn () => false),

            Url::make('Facebook')
                ->hideFromIndex()
                ->clickable()
                ->rules('nullable', 'url')
                ->canSee(fn () => false),

            Url::make('Linkedin')
                ->hideFromIndex()
                ->clickable()
                ->rules('nullable', 'url')
                ->canSee(fn () => false),

            Url::make('Github')
                ->hideFromIndex()
                ->clickable()
                ->rules('nullable', 'url')
                ->canSee(fn () => false),

            MorphToMany::make(__('Roles'), 'roles', \Sereny\NovaPermissions\Nova\Role::class)
                ->canSee(fn () => false),
            MorphToMany::make(__('Permissions'), 'permissions', \Sereny\NovaPermissions\Nova\Permission::class)
                ->searchable()
                ->canSee(fn () => false),
            BelongsToMany::make(__('Quiz'), 'quizzes', Quiz::class),
            HasMany::make(__('Tests'), 'examinations', ExaminationInUser::class),
            Boolean::make(__('Status'), 'status')->sortable()->exceptOnForms(),
            HasMany::make(__('Occupational Certificate'), 'occupationalCertificate', OccupationalCertificate::class),
            HasMany::make(__('Electrical Certificate'), 'electricalCertificate', ElectricalCertificate::class),
            HasMany::make(__('Paper Certificate'), 'paperCertificate', PaperCertificate::class),

            BelongsToMany::make(__('Lesson Learned'), 'lessons', Lesson::class),
            BelongsToMany::make(__('Question Learned'), 'questions', Question::class),
            BelongsToMany::make(__('Mock Quiz Done'), 'mockQuizzes', MockQuiz::class),
        ];
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
            (new GroupNameFilter())->singleSelect(),
            (new UserStatusFilter())
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
        if ($request->viaResource()) {
            return [];
        }

        return [
            (new ImportUser)->standalone()
                ->canSee(fn ($request) => $request->user()->can('create', \App\Models\User::class))
                ->canRun(fn ($request) => $request->user()->can('create', \App\Models\User::class)),
            (new DownloadExcelTemplate())->standalone()
                ->confirmButtonText(__('Download'))
                ->cancelButtonText(__('Cancel'))
                ->onlyOnIndex()
                ->confirmText(__('Are you sure you want to download'))
                ->setType('user')
                ->canSee(fn ($request) => $request->user()->can('create', \App\Models\User::class))
                ->canRun(fn ($request) => $request->user()->can('create', \App\Models\User::class)),
            (new ResetPassword()),
            (new CancelVerification())->showInline(),
            (new AccountVerification())->showInline()
        ];
    }

    public static function afterSave(NovaRequest $request, Model $model)
    {
        // delete groups existed
        $model->groups()->detach();
        $model->groups()->attach($request->group_id);
    }
}
