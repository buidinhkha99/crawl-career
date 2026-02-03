<?php

namespace App\Nova\Flexible\Sections;

use App\Models\Section;
use Laravel\Nova\Fields\Select;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class AttachSectionLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'attach_section';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Saved Section';

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make(__('Section'), 'section')
                ->searchable()
                ->rules('required')
                ->options(fn () => Section::select('id', 'slug')
                        ->get()
                        ->mapWithKeys(fn (Section $section) => [
                            $section->getAttribute('id') => $section->getAttribute('slug'),
                        ]
                        )
                ),
        ];
    }
}
