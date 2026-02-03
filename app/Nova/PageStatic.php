<?php

namespace App\Nova;

use Alexwenzel\DependencyContainer\HasDependencies;
use App\Models\Setting;
use App\Nova\Actions\ReplicateResource;
use App\Nova\Flexible\Resolvers\PageStaticResolver;
use App\Nova\Flexible\Sections\AttachSectionLayout;
use App\Nova\Flexible\Sections\CardsLayout;
use App\Nova\Flexible\Sections\CheckoutLayout;
use App\Nova\Flexible\Sections\ExamList;
use App\Nova\Flexible\Sections\ExamResult;
use App\Nova\Flexible\Sections\ExamRule;
use App\Nova\Flexible\Sections\ExamWork;
use App\Nova\Flexible\Sections\FaqLayout;
use App\Nova\Flexible\Sections\FooterLayout;
use App\Nova\Flexible\Sections\HeaderLayout;
use App\Nova\Flexible\Sections\InfoExam;
use App\Nova\Flexible\Sections\InfoFooterLayout;
use App\Nova\Flexible\Sections\LoginLayout;
use App\Nova\Flexible\Sections\PostDetail;
use App\Nova\Flexible\Sections\PostList;
use App\Nova\Flexible\Sections\SubscriptionLayout;
use App\Nova\Flexible\Sections\UserInfo;
use App\Nova\Traits\HasSeoTrait;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Outl1ne\NovaSortable\Traits\HasSortableRows;
use Trin4ik\NovaSwitcher\NovaSwitcher;
use Whitecube\NovaFlexibleContent\Flexible;

class PageStatic extends Resource
{
    use HasDependencies, HasSortableRows, HasSeoTrait;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\PageStatic>
     */
    public static $model = \App\Models\PageStatic::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
        'path',
        'language',
    ];

    public static function label(): string
    {
        return __('Page Static');
    }

    /**
     * Indicates if the resource should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Number::make(__('Order'), 'order')->readonly()->exceptOnForms(),
            ID::make()->sortable(),
            Select::make(__('Language'), 'language')
                ->options(fn () => Setting::get('languages')->mapWithKeys(fn ($item, $key) => [$item['key'] => $item['value']]))
                ->filterable()
                ->rules('required')
                ->default(Setting::get('default_language'))
                ->help('Page content of other languages will be auto generated'),
            Text::make(__('Path'), 'path')
                ->sortable()
                ->rules('required')
                ->help('Non-default language will automatically set prefixed to that language\'s code'),
            Text::make(__('Title'), 'title')
                ->rules(['required'])
                ->sortable(),
            Boolean::make(__('Required Auth'), 'required_auth')->sortable(),

            NovaSwitcher::make(__('Status'), 'enabled')->withLabels(true: __('Enabled'), false: __('Disabled')),

            Panel::make(__('Sections'), [
                Flexible::make(__('Sections'), 'section')
                    ->hideFromIndex()
                    ->addLayout(AttachSectionLayout::class)
                    ->addLayout(HeaderLayout::class)
                    ->addLayout(CardsLayout::class)
                    ->addLayout(FaqLayout::class)
                    ->addLayout(SubscriptionLayout::class)
                    ->addLayout(LoginLayout::class)
                    ->addLayout(InfoFooterLayout::class)
                    ->addLayout(FooterLayout::class)
                    ->addLayout(PostList::class)
                    ->addLayout(PostDetail::class)
                    ->addLayout(ExamWork::class)
                    ->addLayout(ExamResult::class)
                    ->addLayout(ExamList::class)
                    ->addLayout(ExamRule::class)
                    ->addLayout(CheckoutLayout::class)
                    ->addLayout(UserInfo::class)
                    ->addLayout(InfoExam::class)
                    ->resolver(PageStaticResolver::class)
                    ->button(__('Add Sections'))
                    ->collapsed()
                    ->fullWidth()
                    ->confirmRemove(),
            ])->collapsable()->collapsedByDefault(),

            Panel::make('SEO', $this->seoFields())->collapsable()->collapsedByDefault(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            (new ReplicateResource),
        ];
    }

    public function authorizedToReplicate(Request $request)
    {
        return false;
    }
}
