<?php

namespace App\Nova;

use Alexwenzel\DependencyContainer\HasDependencies;
use App\Nova\Flexible\Resolvers\SectionResolver;
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
use App\Nova\Traits\HasCallbacks;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Whitecube\NovaFlexibleContent\Flexible;

class Section extends Resource
{
    use HasCallbacks;
    use HasDependencies;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Section>
     */
    public static $model = \App\Models\Section::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    public static function label(): string
    {
        return __('Sections');
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
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')->exceptOnForms(),
            Text::make(__('Layout'), 'layout')->exceptOnForms(),
            Flexible::make('Structure')
                ->addLayout(HeaderLayout::class)
                ->addLayout(CardsLayout::class)
                ->addLayout(FooterLayout::class)
                ->addLayout(FaqLayout::class)
                ->addLayout(SubscriptionLayout::class)
                ->addLayout(LoginLayout::class)
                ->addLayout(InfoFooterLayout::class)
                ->addLayout(PostList::class)
                ->addLayout(CheckoutLayout::class)
                ->addLayout(PostDetail::class)
                ->addLayout(ExamWork::class)
                ->addLayout(UserInfo::class)
                ->addLayout(InfoExam::class)
                ->addLayout(ExamResult::class)
                ->addLayout(ExamList::class)
                ->addLayout(ExamRule::class)
                ->resolver(SectionResolver::class)
                ->limit()
                ->rules('required')
                ->fullWidth()
                ->confirmRemove(),

            BelongsToMany::make(__('Pages'), 'pages', PageStatic::class)
                ->fields(fn () => [
                    Number::make(__('Order'), 'order')->rules('required'),
                    Text::make(__('Key'), 'key'),
                ])->rules('required')
                ->required(),
        ];
    }

    public static function beforeSave(Request $request, $model)
    {
        $structure = $request->structure;
        if (! $structure || ! is_array($structure) || count($structure) <= 0) {
            return;
        }

        $structure = ((object) $structure[0]);
        if (! $structure) {
            return;
        }

        $model->layout = $structure->layout;
        $model->name = $structure->attributes['name'];
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
        return [];
    }
}
