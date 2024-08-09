<?php

namespace App\Nova\Flexible\Sections;

use App\Models\Post;
use App\Nova\Flexible\Components\Background;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Text;
use Outl1ne\NovaMediaHub\Models\Media;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class PostDetail extends Layout implements Section
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'post-detail';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Post Detail';

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

            Text::make(__('List Post path'), 'list_post_path'),
        ];
    }

    public function collapsedPreviewAttribute()
    {
        return 'name';
    }

    public function cacheable(): bool
    {
        return false;
    }

    public function cacheableData(): array
    {
        return [];
    }

    public function render(Request $request, $id = null): mixed
    {
        $attributes = $this->attributes;

        $post = Post::where('slug', $request->route()->parameter('slug'))->firstOrFail();

        return [
            'id' => $id,
            'type' => 'PostDetail',
            'background' => $this->backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'thumbnail' => ! $post->thumbnail ? null : Media::find($post->thumbnail)?->thumbnailUrl,
            'cover' => ! $post->cover ? null : Media::find($post->cover)?->url,
            'author' => [
                'name' => $post->author,
            ],
            'title' => $post->title,
            'posted_at' => $post->created_at,
            'tags' => $post->tags->map(fn ($tag) => [
                'url' => $attributes['list_post_path']."?tags=$tag->slug",
                'title' => $tag->name,
            ]),
            'content' => $post->content,
            'seo' => [
                'seo_title' => $post->seo_title,
                'seo_description' => $post->seo_description,
                'seo_keywords' => $post->seo_keywords,
                'seo_og_image_url' => $post->seo_og_image_url,
            ],
        ];
    }
}
