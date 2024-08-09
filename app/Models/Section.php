<?php

namespace App\Models;

use App\Nova\Flexible\Sections\CardsLayout;
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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Section extends Model
{
    use HasFactory, HasSlug;

    private $flexible_structure = null;

    protected $fillable = ['layout', 'order', 'structure', 'name'];

    protected $casts = [
        'structure' => 'array',
    ];

    protected $layouts = [
        'footer' => FooterLayout::class,
        'header' => HeaderLayout::class,
        'card' => CardsLayout::class,
        'faq' => FaqLayout::class,
        'subscription' => SubscriptionLayout::class,
        'post' => PostList::class,
        'post-detail' => PostDetail::class,
        'login' => LoginLayout::class,
        'info-footer' => InfoFooterLayout::class,
        'exam_work' => ExamWork::class,
        'user_info' => UserInfo::class,
        'exam_result' => ExamResult::class,
        'exam_rule' => ExamRule::class,
        'info_exam' => InfoExam::class,
        'exam_list' => ExamList::class,
    ];

    public function pages()
    {
        return $this->belongsToMany(PageStatic::class)
            ->using(PageStaticSection::class)
            ->withPivot(['order', 'key']);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getFlexibleStructure()
    {
        $layout = Arr::get($this->layouts, $this->structure['layout'] ?? '');
        if (! $layout) {
            return $this->structure;
        }

        $this->flexible_structure = new $layout(null, null, null, $this->structure['key'], $this->structure['attributes'], null);
        $this->flexible_structure->setModel($this);

        return $this->flexible_structure;
    }

    public function render(Request $request, $id = null, $language = null, $locale = null): mixed
    {
        return $this->getFlexibleStructure()->setLanguage($language)->setLocale($locale)->render($request, $id);
    }

    public function cacheable(): bool
    {
        $structure = $this->getFlexibleStructure();

        return $structure instanceof \App\Nova\Flexible\Sections\Section && $structure->cacheable();
    }

    public function structure_cache_key($locale): string
    {
        return "section_structures:{$this->getFlexibleStructure()->key()}:$locale";
    }
}
