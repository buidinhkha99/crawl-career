<?php

namespace App\Nova\Flexible\Sections;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\PostGroup;
use App\Models\Tag;
use App\Nova\Flexible\Components\Background;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Outl1ne\NovaMediaHub\Models\Media;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class PostList extends Layout implements Section
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'post';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Posts';

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

            Text::make(__('Detail Post path'), 'detail_post_path'),

            Select::make(__('Post Groups'), 'post_groups')->options(
                PostGroup::all('id', 'name')->keyBy('id')->map(fn ($item) => $item->name)
            )->rules('required'),
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

        $filterTags = $request->query('tags', null);
        if ($filterTags) {
            $filterTags = explode(',', trim($filterTags));
        }

        $query = Post::whereHas('groups', fn ($query) => $query->where('post_groups.id', $attributes['post_groups']))
            ->where('status', PostStatus::Published)
            ->with('tags')
            ->orderBy('id', 'desc');
        if ($filterTags) {
            $query = $query->whereHas('tags', fn ($q) => $q->whereIn('tags.slug->'.config('app.locale'), $filterTags));
        }
        $posts = $query->paginate(5);

        return [
            'id' => $id,
            'type' => 'Post',
            'background' => $this->backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'current_page' => $posts->currentPage(),
            'total' => $posts->total(),
            'per_page' => $posts->perPage(),
            'type_card' => 'img_round', // img_square, img_round
            'list_card' => $posts->getCollection()->transform(fn ($post) => [
                'url' => preg_replace('/{.*?}/m', $post->slug, $attributes['detail_post_path']),
                'img' => ! $post->thumbnail ? null : Media::find($post->thumbnail)?->thumbnailUrl,
                'title' => $post->title,
                'slug' => $post->slug,
                'date' => $post->created_at,
                'description' => $post->description,
                'tags' => $post->tags->map(fn ($tag) => [
                    'url' => $request->url()."?tags=$tag->slug",
                    'title' => $tag->name,
                ]),
            ]),
            'popular' => [
                'title' => 'Popular',
                'type_card' => 'image_round',
                'data' => Post::featured()->get()->map(fn ($post) => [
                    'url' => preg_replace('/{.*?}/m', $post->slug, $attributes['detail_post_path']),
                    'img' => ! $post->thumbnail ? null : Media::find($post->thumbnail)?->thumbnailUrl,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'description' => $post->description,
                ]),
            ],
            'topic' => [
                'title' => 'Recommend Topic',
                'type_card' => 'img_round',
                'data' => Tag::withCount('posts')
                    ->whereHas('posts', fn ($query) => $query->where('status', PostStatus::Published))
                    ->orderBy('posts_count', 'desc')
                    ->get(['name', 'slug'])
                    ->map(fn ($tag) => [
                        'url' => $request->url()."?tags=$tag->slug",
                        'title' => $tag->name,
                    ]),
            ],
        ];
    }
}
