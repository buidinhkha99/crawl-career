<?php

namespace App\Traits;

use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOTools;

trait RenderSeoTrait
{
    public function setCommonSEO($seo)
    {
        $seo['seo_title'] = empty($seo['seo_title']) ? Setting::get('seo_title') : $seo['seo_title'];
        $seo['seo_description'] = empty($seo['seo_description']) ? Setting::get('seo_description') : $seo['seo_description'];
        $seo['seo_keywords'] = empty($seo['seo_keywords']) ? Setting::get('seo_keywords') : $seo['seo_keywords'];
        $seo['seo_og_image_url'] = empty($seo['seo_og_image_url']) ? Setting::get('seo_og_image_url') : $seo['seo_og_image_url'];

        SEOTools::setTitle($seo['seo_title']);
        SEOTools::metatags()->setTitleDefault($seo['seo_title']);

        SEOTools::setDescription($seo['seo_description']);

        SEOTools::metatags()->setKeywords(is_array($seo['seo_keywords']) ? implode(',', $seo['seo_keywords']) : $seo['seo_keywords']);

        SEOTools::addImages($seo['seo_og_image_url']);
    }
}
