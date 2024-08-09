<?php

namespace App\Nova\Flexible\Sections;

use Exception;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class CheckoutLayout extends Layout
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'checkout';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Checkout';

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
        ];
    }

    public function render(Request $request, $id = null): mixed
    {
        $attributes = $this->attributes;

        return [
            'type' => 'PlaceOrder',
            'id' => $id,
        ];
    }
}
