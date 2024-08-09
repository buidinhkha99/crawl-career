<?php

namespace App\Nova;

use App\Enums\PostStatus;
use App\Nova\Traits\HasSeoTrait;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Murdercode\TinymceEditor\TinymceEditor;
use NovaAttachMany\AttachMany;
use Outl1ne\NovaMediaHub\Nova\Fields\MediaHubField;
use SimpleSquid\Nova\Fields\Enum\Enum;
use Spatie\TagsField\Tags;

class Post extends Resource
{
    use HasSeoTrait;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Post>
     */
    public static $model = \App\Models\Post::class;

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
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            MediaHubField::make(__('Cover'), 'cover'),
            MediaHubField::make(__('Thumbnail'), 'thumbnail'),
            Text::make(__('Title'), 'title')->required()->rules('required')->sortable(),
            Text::make('Slug')->onlyOnDetail()->readonly(),
            Text::make(__('Author'))->sortable(),
            Textarea::make(__('Description'), 'description'),
            Enum::make(__('Status'))->attach(PostStatus::class)->default(PostStatus::Draft)->rules('required')->required()->sortable(),
            TinymceEditor::make(__('Content'), 'content')->fullWidth(),
            Tags::make(__('Tags'))->withLinkToTagResource(),
            Boolean::make(__('Featured'))->sortable(),

            BelongsToMany::make(__('Groups'), 'groups', PostGroup::class),

            AttachMany::make(__('Groups'), 'groups', PostGroup::class)->rules('min:1')
                ->showCounts()
                ->showPreview(),

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
        return [];
    }

    public static function label(): string
    {
        return __('Posts');
    }
}
