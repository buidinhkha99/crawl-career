<?php

namespace App\Nova\Observer;

use App\Exceptions\AppException;
use App\Models\PageStatic;

class PageStaticObserver
{
    public function saving(PageStatic $page)
    {
        if (PageStatic::where('id', '!=', $page->id)->where('path', $page->path)->where('language', $page->language)->exists()) {
            throw new AppException('Page with path '.$page->path.' and '.$page->language.' language already exists');
        }
    }
}
