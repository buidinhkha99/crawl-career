<?php

namespace App\Nova\Observer;

use App\Exceptions\AppException;
use App\Models\PageStaticSection;

class PageStaticSectionObserver
{
    /**
     * @throws AppException
     */
    public function saving(PageStaticSection $pageStaticSection)
    {
        if (empty($pageStaticSection->key) || ! PageStaticSection::where('page_static_id', $pageStaticSection->page_static_id)
            ->where('key', $pageStaticSection->key)
            ->exists()) {
            return;
        }

        throw new AppException("Key '$pageStaticSection->key' used in this page");
    }
}
