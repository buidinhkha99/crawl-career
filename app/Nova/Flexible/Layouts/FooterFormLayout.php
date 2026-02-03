<?php

namespace App\Nova\Flexible\Layouts;

use App\Models\Form;
use Outl1ne\MultiselectField\Multiselect;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class FooterFormLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'form-footer';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Footer Form Layout';

    /**
     * The maximum amount of this layout type that can be added
     * Can be set in custom layouts
     */
    protected $limit = 1;

    /**
     * Get the fields displayed by the layout.
     *
     * @throws \Exception
     */
    public function fields(): array
    {
        return [
            Multiselect::make(__('Form'), 'form_id')
                ->options(Form::all()->pluck('title', 'id'))
                ->singleSelect()
                ->rules(['required']),
        ];
    }

    public function collapsedPreviewAttribute()
    {
        return 'title';
    }
}
