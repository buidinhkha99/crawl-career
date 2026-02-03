<?php

namespace Salt\TitleDashboard;

use Laravel\Nova\Card;

class TitleDashboard extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';
    public $title = '';

    public function jsonSerialize(): array
    {
        return array_merge([
            'width' => $this->width,
            'height' => $this->height,
            'title' => $this->title,
        ], parent::jsonSerialize());
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'title-dashboard';
    }
}
