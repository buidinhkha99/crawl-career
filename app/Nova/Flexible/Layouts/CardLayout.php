<?php

namespace App\Nova\Flexible\Layouts;

use AlexAzartsev\Heroicon\Heroicon;
use Alexwenzel\DependencyContainer\DependencyContainer;
use App\Nova\Flexible\Components\Button;
use App\Nova\Flexible\Components\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Murdercode\TinymceEditor\TinymceEditor;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class CardLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'card-layout';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Card Layout';

    /**
     * Get the fields displayed by the layout.
     *
     * @throws \Exception
     */
    public function fields(): array
    {
        return [
            Select::make(__('Layout'), 'layout')->options([
                'image-info-1:1' => __('Image-Info (1:1)'),
                'info-image-1:1' => __('Info-Image (1:1)'),
                'image-info-1:2' => __('Image-Info (1:2)'),
                'info-image-2:1' => __('Info-Image (2:1)'),
                'only-info' => __('Only-Info'),
                'only-image' => __('Only-Image'),
                'image-info_icon-1:1' => __('Image-Info_Icon (1:1)'),
                'info_icon-image-1:1' => __('Info_Icon-Image (1:1)'),
            ])->default('image-info-1:1')->rules('required'),

            Text::make(__('Title'), 'title'),
            TinymceEditor::make(__('Description'), 'description')->fullWidth(),

            Number::make(__('Max lines'), 'description_ellipsis')->min(0)->step(1)->default(5),

            ...Image::fields(),

            Select::make(__('Click detail'), 'click_detail_option')->options([
                'none' => 'None',
                'direct' => 'Direct',
                'button' => 'Button',
            ])->default('none')->rules('required'),

            DependencyContainer::make([
                Text::make(__('Detail Link'), 'detail_button_link')->default('#'),
            ])->dependsOn('click_detail_option', 'direct'),

            DependencyContainer::make(
                Button::fields(__('Detail'), 'detail_')
            )->dependsOn('click_detail_option', 'button'),

            Flexible::make(__('Description Icon'), 'description_icon')->addLayout('Description Icon', 'description_icon', [
                Heroicon::make(__('Icon'), 'icon'),
                Text::make(__('Title'), 'title'),
            ])->button(__('Add Description Icon')),

        ];
    }

    public function collapsedPreviewAttribute()
    {
        return 'title';
    }
}
