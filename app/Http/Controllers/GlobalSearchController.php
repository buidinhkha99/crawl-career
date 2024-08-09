<?php

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;
use App\Traits\RenderSeoTrait;
use App\Traits\RenderSettingGlobalTrait;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Outl1ne\NovaMediaHub\Models\Media;

class GlobalSearchController extends Controller
{
    use RenderSeoTrait, RenderSettingGlobalTrait;

    public function show(Request $request)
    {
        $data = collect([
            'seo' => [
                'seo_title' => null,
                'seo_description' => null,
                'seo_keywords' => null,
                'seo_og_image_url' => null,
            ],
        ]);

        $search_trim = Str::of($request->get('text-search'))->trim();

        // get model search
        $tables = Setting::get('tables_search')->map(function ($table) {
            return [
                'name' => Arr::get($table, 'layout'),
                'attributes' => collect(Arr::get($table, 'attributes')),
            ];
        });

        // get data with each model
        $metadata = $tables->map(function ($table) use ($search_trim, $request) {
            if ($table['name'] == 'posts') {
                $query = Post::where('title', 'LIKE', "%$search_trim%")->where('status', PostStatus::Published);
                $group_ids = json_decode(Arr::get($table['attributes'], 'group_post_search'));

                if (! empty($group_ids)) {
                    $query->whereHas('groups', function ($q) use ($group_ids) {
                        $q->whereIn('post_groups.id', $group_ids);
                    });
                }

                $filterTags = $request->query('tags', null);
                if ($filterTags) {
                    $filterTags = explode(',', trim($filterTags));
                }

                if ($filterTags) {
                    $query = $query->whereHas('tags', fn ($q) => $q->whereIn('tags.slug->'.config('app.locale'), $filterTags));
                }

                $posts = $query->paginate(5);

                return [
                    'id' => '',
                    'type' => 'Post',
                    'background' => $this->backgroundRender(
                        Arr::get([], 'background_option', 'color'),
                        null
                    ),
                    'current_page' => $posts->currentPage(),
                    'total' => $posts->total(),
                    'per_page' => $posts->perPage(),
                    'type_card' => 'img_round', // img_square, img_round
                    'list_card' => $posts->getCollection()->transform(fn ($post) => [
                        'url' => preg_replace('/{.*?}/m', $post->slug, Arr::get($table['attributes'], 'path_detail_post')),
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
                            'url' => preg_replace('/{.*?}/m', $post->slug, Arr::get($table['attributes'], 'path_detail_post')),
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

            return [];
        });

        $data->push('components', $metadata);
        $this->setCommonSEO($data['seo']);
        $data->put('title', SEOTools::getTitle());

        Inertia::share('lang', $request->session()->get('lang'));
        Inertia::share('setting', $this->getSettingGlobal());
        // return back results
        return Inertia::render('ResultSearchGlobal', ['components' => $metadata]);
    }
}
