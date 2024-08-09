<?php

namespace App\Nova\Flexible\Layouts;

use App\Nova\Flexible\Components\Icon;
use App\Nova\Flexible\Components\Logo;
use Laravel\Nova\Fields\Textarea;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class FooterInformationLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'information-footer';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Footer Information Layout';

    /**
     * Get the fields displayed by the layout.
     *
     * @throws \Exception
     */
    public function fields(): array
    {
        return [
            ...Logo::fields(),
            Textarea::make(__('Introduction'), 'footer_information_introduction'),
            Flexible::make(__('Icon'), 'footer_information_icon')
                ->addLayout(new Layout(__('Icon'), 'footer_information_icon_layout', Icon::fields()))
                ->fullWidth()
                ->button(__('Add Icon'))
                ->confirmRemove(),
        ];
    }
}
