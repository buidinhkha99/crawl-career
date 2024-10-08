<?php

namespace App\Providers;

use App\Enums\SettingType;
use App\Exceptions\Handler;
use App\Http\Middleware\OverrideSectionEditModeWhenUpdate;
use App\Models\PageStaticSection;
use App\Models\Setting;
use App\Nova\Attendance;
use App\Nova\Classroom;
use App\Nova\Customization;
use App\Nova\Dashboards\Main;
use App\Nova\Dashboards\Review;
use App\Nova\Flexible\Components\Background;
use App\Nova\Form;
use App\Nova\LMS\Certificates\ElectricalCertificate;
use App\Nova\LMS\Certificates\OccupationalCertificate;
use App\Nova\LMS\Certificates\PaperCertificate;
use App\Nova\LMS\Exam;
use App\Nova\LMS\ExaminationInReport;
use App\Nova\LMS\Lesson;
use App\Nova\LMS\MockQuiz;
use App\Nova\LMS\ObjectGroupCertificate;
use App\Nova\LMS\Question;
use App\Nova\LMS\QuestionType;
use App\Nova\LMS\Quiz;
use App\Nova\LMS\Topic;
use App\Nova\Observer\FormObserver;
use App\Nova\Observer\LessonObserver;
use App\Nova\Observer\MockQuizObserver;
use App\Nova\Observer\PageStaticObserver;
use App\Nova\Observer\PageStaticSectionObserver;
use App\Nova\Observer\QuestionObserver;
use App\Nova\Observer\QuestionOptionObserver;
use App\Nova\Observer\RoleObserver;
use App\Nova\Observer\UserObserver;
use App\Nova\PageStatic;
use App\Nova\Permission;
use App\Nova\Post;
use App\Nova\PostGroup;
use App\Nova\Role;
use App\Nova\Section;
use App\Nova\Tag;
use App\Nova\Traits\HasSeoTrait;
use App\Nova\Traits\Layout\SettingSearchPostLayout;
use App\Nova\User;
use App\Nova\UserGroup;
use App\Rules\DurationValidate;
use App\Rules\QuestionAmountValidate;
use App\Rules\ScorePassValidate;
use App\Rules\SettingLanguagesRequiresDefault;
use App\Rules\SettingLanguagesUnique;
use Badinansoft\LanguageSwitch\LanguageSwitch;
use Exception;
use Harishdurga\LaravelQuiz\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\ActionEvent;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Exceptions\NovaExceptionHandler;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Menu\Menu;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Laravel\Nova\Observable;
use Laravel\Nova\Panel;
use Murdercode\TinymceEditor\TinymceEditor;
use Outl1ne\NovaColorField\Color;
use Outl1ne\NovaMediaHub\MediaHub;
use Outl1ne\NovaSettings\NovaSettings;
use Outl1ne\NovaSimpleRepeatable\SimpleRepeatable;
use Salt\ResetPassword\ResetPassword;
use Sereny\NovaPermissions\NovaPermissions;
use Whitecube\NovaFlexibleContent\Flexible;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    use HasSeoTrait;

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        ActionEvent::saving(function ($actionEvent) {
            return false;
        });

        // This middleware is for when updating section, fields are not using default value; therefore some fields maybe null
        $this->app->get('router')?->pushMiddlewareToGroup('nova', OverrideSectionEditModeWhenUpdate::class);

        Nova::serving(function (ServingNova $event) {
            $this->bootSettings();
        });

        Nova::withBreadcrumbs();

        Nova::footer(function ($request) {
            return Blade::render('<div class="mt-8 leading-normal text-xs text-gray-500 space-y-1">
            <p class="text-center">A Product By <a class="link-default" href="https://brocos.io">BroCoS</a></p>
            <p class="text-center">© 2023 BroCoS LLC.</p>
        </div>');
        });

        Nova::style('custom.css', asset('css/nova_custom.css'));

        Nova::mainMenu(function (Request $request) {
            return [
                MenuSection::dashboard(Main::class)
                    ->icon('chart-square-bar')
                    ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin())
                        || Auth::user()->hasPermissionTo('viewDashboardExaminations') || Auth::user()->hasPermissionTo('viewDashboardReview') )),
                MenuSection::make(__('User'), [
                    MenuItem::resource(UserGroup::class),
                    MenuItem::resource(User::class),
                ])->collapsedByDefault()->icon('user-group'),
                MenuSection::make(__('Classroom'), [
                    MenuItem::resource(Classroom::class),
                    MenuItem::resource(Attendance::class),
                ])->icon('question-mark-circle')->collapsedByDefault(),
                MenuSection::make(__('Training content'), [
                    MenuItem::resource(Topic::class),
                    MenuItem::resource(Question::class),
                    MenuItem::resource(Lesson::class),
                ])->icon('question-mark-circle')->collapsedByDefault(),
                MenuSection::make(__('Examinations'), [
                    MenuItem::resource(Exam::class),
                    MenuItem::resource(Quiz::class),
                    MenuItem::resource(ExaminationInReport::class),
                ])->icon('document-text')->collapsedByDefault(),

               MenuSection::make(__('Review'), [
                   MenuItem::resource(MockQuiz::class),
                   MenuItem::make(__('Random Quiz'))->path('/settings/quiz-random')->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin())
                           || Auth::user()->hasPermissionTo('view'.SettingType::QuizRandom))),
               ])->icon('document-text')->collapsedByDefault(),

                MenuSection::make(__('Certificate'), [
                    MenuItem::resource(OccupationalCertificate::class),
                    MenuItem::resource(ElectricalCertificate::class),
                    MenuItem::resource(PaperCertificate::class),
                ])->icon('academic-cap')->collapsedByDefault(),

                MenuSection::make(__('Configuration'), [
                    MenuItem::make(__('Exam Rule'))->path('/settings/rule')
                        ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin())
                            || Auth::user()->hasPermissionTo('view'.SettingType::Rule))),
                    MenuItem::resource(QuestionType::class)
                        ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin()))),
                    MenuItem::make(__('Exam Results PDF'))->path('/settings/exam-result-pdf-page')->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin()))),
                    MenuItem::make(__('PDF Report'))->path('/settings/pdf-report')
                        ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin()))),
                    MenuItem::make(__('Electrical Certificate'))->path('/settings/electrical-certificate')
                        ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin()))),
                    MenuItem::make(__('Occupational Certificate'))->path('/settings/occupation-certificate')
                        ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin()))),
                    MenuItem::make(__('Paper Certificate'))->path('/settings/paper-certificate')
                        ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin()))),
                    MenuItem::make(__('PDF Certificate'))->path('/settings/pdf-certificate')->canSee(fn () => Auth::user()),
                    MenuItem::resource(ObjectGroupCertificate::class)->canSee(fn () => Auth::user()),
                ])->collapsedByDefault()->icon('cog'),
                MenuSection::make(__('Interface'), [
                    MenuItem::resource(PageStatic::class),
                    MenuItem::resource(Form::class),
                    MenuItem::resource(Section::class),
                ])->icon('newspaper')->collapsedByDefault(),
                MenuSection::make(__('Roles & Permissions'), [
                    MenuItem::resource(Role::class),
                    MenuItem::resource(Permission::class),
                ])->icon('shield-check')->collapsable(),

                MenuSection::resource(Customization::class)->icon('pencil-alt'),

                MenuSection::make(__('Media Hub'))
                    ->path('/media-hub')
                    ->icon('upload')
                    ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin())
                        || Auth::user()->hasPermissionTo(SettingType::MediaHub))),

                MenuSection::make(__('Posts'), [
                    MenuSection::resource(PostGroup::class)->icon('collection'),
                    MenuSection::resource(Post::class)->icon('pencil'),
                    MenuSection::resource(Tag::class)->icon('tag'),
                ])->icon('document-text')->collapsedByDefault(),

                MenuSection::make(__('Setting'), [
                    MenuItem::make('SEO')->path('/settings/seo')
                        ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin())
                            || Auth::user()->hasPermissionTo('view' . SettingType::Seo))),
                    MenuItem::make(__('General Page'))->path('/settings/general-page')
                        ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin())
                            || Auth::user()->hasPermissionTo('view' . SettingType::GeneralPage))),
                    MenuItem::make(__('Language'))->path('/settings/language')
                        ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin())
                            || Auth::user()->hasPermissionTo('view' . SettingType::Language))),
                    MenuItem::make(__('Search'))->path('/settings/search')
                        ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin())
                            || Auth::user()->hasPermissionTo('view' . SettingType::Search))),
                    MenuItem::make(__('Error Pages'))->path('/settings/error-page')
                        ->canSee(fn () => Auth::user() && ((method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin())
                            || Auth::user()->hasPermissionTo('view' . SettingType::ErrorPage))),
                ])->icon('adjustments')->collapsedByDefault(),
            ];
        });

        Nova::userMenu(function (Request $request, Menu $menu) {
            $menu->prepend(
                MenuItem::make(
                    __('Update profile'),
                    "/resources/users/{$request->user()?->getKey()}/edit"
                )
            );

            $menu->prepend(
                MenuItem::make(
                    __('Change password'),
                    '/reset-password'
                )
            );

            return $menu;
        });

        Observable::make(PageStaticSection::class, PageStaticSectionObserver::class);
        Observable::make(\App\Models\PageStatic::class, PageStaticObserver::class);
        Observable::make(\App\Models\Form::class, FormObserver::class);
        Observable::make(\App\Models\User::class, UserObserver::class);
        Observable::make(QuestionOption::class, QuestionOptionObserver::class);
        Observable::make(\App\Models\Role::class, RoleObserver::class);
        Observable::make(\App\Models\MockQuiz::class, MockQuizObserver::class);
        Observable::make(\App\Models\Question::class, QuestionObserver::class);
        Observable::make(\App\Models\Lesson::class, LessonObserver::class);
    }

    /**
     * Register the Nova routes.
     */
    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewNova', function ($user) {
            return $user->isSuperAdmin() || $user->hasPermissionTo('viewAdminPortal');
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     */
    protected function dashboards(): array
    {
        return [
            new Main,
        ];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     */
    public function tools(): array
    {
        return [
            new ResetPassword,
            new NovaSettings,

            MediaHub::make()
                // You can choose to hide the Tool from the sidebar
                ->hideFromMenu()

                // Optionally add additional fields to Media items
                ->withCustomFields(
                    ['copyright' => __('Copyright')],
                    overwrite: false
                ),

            NovaPermissions::make()->disablePermissions()
                ->hideFieldsFromRole([
                    'id',
                    'guard_name',
                ])
                ->hideFieldsFromPermission([
                    'id',
                    'guard_name',
                    'users',
                    'roles',
                ]),
            new LanguageSwitch(),
        ];
    }

    public function bootSettings()
    {
        if (!Str::startsWith(request()->path(), 'nova-vendor/nova-settings')) {
            return;
        }

        NovaSettings::clearFields();

        try {
            NovaSettings::addSettingsFields([
                Panel::make('SEO', $this->seoFields())->collapsable()->collapsedByDefault(),
            ], [], 'seo');

            NovaSettings::addSettingsFields([
                Panel::make(__('Background'), Background::fields()),
                Panel::make(__('Font'), [
                    Color::make(__('Font Color'), 'font_color')->swatches(),
                    Text::make(__('Font Family'), 'font_name'),
                    Textarea::make(__('Fonts'), 'fonts'),
                ]),
                Panel::make(__('Favicon'), [
                    URL::make(__('Favicon URL'), 'favicon'),
                ]),
                Panel::make(__('Button'), [
                    Color::make(__('Button Color Background'), 'button_color_background')->swatches(),
                    Color::make(__('Icon Color'), 'button_icon_color')->swatches(),
                    Color::make(__('Text Color'), 'button_text_color')->swatches(),
                ]),
                Panel::make(__('Form'), [
                    Color::make(__('Color Title'), 'color_text_title_form')
                        ->swatches(),
                    Color::make(__('Background Input'), 'background_input_form')
                        ->swatches(),
                    Color::make(__('Color Border Input'), 'color_border_input_form')
                        ->swatches(),
                    Color::make(__('Color Placeholder Input'), 'color_placeholder_input_form')
                        ->swatches(),
                ]),
            ], [], 'general-page');

            NovaSettings::addSettingsFields([
                Panel::make(__('Language'), [
                    SimpleRepeatable::make(__('Language'), 'languages', [
                        Text::make(__('Name'), 'value')->rules(['required'])->placeholder('English'),
                        Select::make('Code', 'key')
                            ->options(config('languages'))
                            ->rules(['required'])
                            ->searchable(),
                        Boolean::make(__('Default'), 'default')->default(false),
                    ])->addRowLabel(__('Add Language'))->minRows(1)->rules(fn ($request) => [
                        new SettingLanguagesRequiresDefault($request),
                        new SettingLanguagesUnique($request),
                    ]),
                ]),

                Panel::make(__('Country default language'), [
                    SimpleRepeatable::make(__('Country & Language Sets'), 'country_language', [
                        Select::make(__('Country'), 'country')
                            ->options(fn () => config('countries'))
                            ->rules(['required'])
                            ->searchable(),
                        Select::make(__('Language'), 'language')
                            ->options(fn () => Setting::get('languages')->mapWithKeys(fn ($item, $key) => [$item['key'] => $item['value']]))
                            ->rules(['required']),
                    ])->addRowLabel(__('Add Country & Language Set')),
                ]),
            ], [], 'language');

            NovaSettings::addSettingsFields([
                Panel::make(__('Global Search'), [
                    Flexible::make(__('Tables Search'), 'tables_search')
                        ->button(__('Select Table'))
                        ->addLayout(SettingSearchPostLayout::class),
                ]),
            ], [], 'search');

            NovaSettings::addSettingsFields([
                Code::make(__('Default Error Page'), 'content_default_page_error')->language('javascript')->stacked()->fullWidth()->height(500),
                Panel::make(__('Error Pages For Code Status'), [
                    SimpleRepeatable::make(__('Error Pages'), 'error_pages', [
                        Text::make(__('Status Code'), 'status_code')->rules(['required']),
                        Code::make(__('Content'), 'content_page_error')->language('javascript')->stacked()->fullWidth()->height(300),
                    ])->addRowLabel(__('Add Error Page'))->fullWidth(),
                ]),

            ], [], 'error-page');

            NovaSettings::addSettingsFields([
                Panel::make(__('Rule'), [
                    TinymceEditor::make(__('Rule'), 'rule')->fullWidth(),
                ]),
            ], [], 'rule');

            NovaSettings::addSettingsFields([
                Panel::make(__('Exam Results PDF'), [
                    Code::make(__('Exam results PDF'), 'exam_result_pdf')->language('javascript')->stacked()->fullWidth()->height(800),
                ]),
            ], [], 'exam-result-pdf-page');

            NovaSettings::addSettingsFields([
                Panel::make(__('Fields For Export PDF'), [
                    Boolean::make(__('Company Name'), 'company_name'),
                    Boolean::make(__('Place'), 'place'),
                    Boolean::make(__('Time'), 'date_time'),
                    Boolean::make(__('Title'), 'title'),
                    Boolean::make(__('Note'), 'note'),
                    Boolean::make(__('Verifier title'), 'verifier'),
                    Boolean::make(__('Represent'), 'represent'),
                    Boolean::make(__('Reporter title'), 'reporter'),
                ]),
                Panel::make(__('PDF Report Page'), [
                    Code::make(__('PDF report page content'), 'content_page_pdf_report')->language('javascript')->stacked()->fullWidth()->height(800),
                ]),

            ], [], 'pdf-report');

            NovaSettings::addSettingsFields([
                Panel::make(__('Random'), [
                    Text::make(__('Duration (minute)'), 'duration')->rules(new DurationValidate)
                        ->default(20)->required(),
                    Number::make(__('Score Pass'), 'score_pass_quiz')
                        ->rules(new ScorePassValidate)
                        ->step(0.01)
                        ->default(5)->required(),
                    Text::make(__('Question Amount'), 'question_amount_quiz')->rules(new QuestionAmountValidate)->required(),
                    SimpleRepeatable::make(__('Kit'), 'kit', [
                        Select::make(__('Topics'), 'topics')
                            ->options(fn () => \App\Models\Topic::pluck('name', 'name'))
                            ->searchable(),
                        Text::make(__('Amount'), 'amount')->rules('min:1', 'integer'),
                    ])->addRowLabel(__('Add topic'))
                        ->minRows(1)->required(),
                ]),
            ], [], 'quiz-random');

            NovaSettings::addSettingsFields([
                Panel::make(__('Electrical Certificate'), [
                    Code::make(__('Electrical Certificate'), 'pdf_electrical_certificate')->language('javascript')->stacked()->fullWidth()->height(800),
                ]),
            ], [], 'electrical-certificate');

            NovaSettings::addSettingsFields([
                Panel::make(__('Occupational Certificate'), [
                    Code::make(__('Occupational Certificate'), 'pdf_occupational_certificate')->language('javascript')->stacked()->fullWidth()->height(800),
                ]),
            ], [], 'occupation-certificate');

            NovaSettings::addSettingsFields([
                Panel::make(__('Occupational Certificate'), [
                    Text::make(__('Place'), 'place_occupational')->default(fn () => __('Lào Cai'))->rules('required'),
                    Date::make(__('Complete From'), 'complete_from')->rules('required')->default(fn () => now()),
                    Date::make(__('Complete To'), 'complete_to')->rules('required')->default(fn () => now()),
                    Text::make(__('Director Name'), 'director_name_occupational')->rules('required'),
                    Image::make(__('Signature Image'), 'signature_photo_occupational')->required(),
                    Date::make(__('Effective To'), 'effective_to')->rules('required')->default(fn () => now()),
                ]),

                Panel::make(__('Electrical Certificate'), [
                    Text::make(__('Director Name'), 'director_name_electric')->rules('required'),
                    Image::make(__('Signature Image'), 'signature_photo_electric')->required(),
                ]),

                Panel::make(__('Paper Certificate'), [
                    Text::make(__('Work unit'), 'work_unit')->rules('required')->default('Chi nhánh Luyện đồng Lào Cai - VIMICO'),
                    Text::make(__('Place'), 'place_paper')->default(fn () => __('Lào Cai'))->rules('required'),
                    Text::make(__('Director Name'), 'director_name_paper')->rules('required'),
                    Image::make(__('Signature Image'), 'signature_photo_paper')->required(),
                ]),
            ], [], 'pdf-certificate');

            NovaSettings::addSettingsFields([
                Panel::make(__('Paper Certificate'), [
                    Code::make(__('Paper Certificate'), 'pdf_paper_certificate')->language('javascript')->stacked()->fullWidth()->height(800),
                ]),
            ], [], 'paper-certificate');
        } catch (Exception $e) {
            return;
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function registerExceptionHandler()
    {
        app()->bind(NovaExceptionHandler::class, Handler::class);
    }
}
