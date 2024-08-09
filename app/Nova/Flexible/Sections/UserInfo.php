<?php

namespace App\Nova\Flexible\Sections;

use App\Nova\Flexible\Components\Background;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class UserInfo extends Layout
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'user_info';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'User Info';

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
            ...Background::fields(),
            Text::make(__('Agree Text'), 'agree')
                ->sortable(),
            Text::make(__('Next Link'), 'next_link'),
        ];
    }

    public function collapsedPreviewAttribute(): string
    {
        return 'name';
    }

    public function cacheable(): bool
    {
        return true;
    }

    public function render(Request $request, $id = null): mixed
    {
        $attributes = $this->attributes;
        $user = Auth::user();

        return [
            'id' => $id,
            'type' => 'UserInfo',
            'background' => self::backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'avatar' => $user?->avatar_url ?: '/storage/default_avatar_user.png',
            'full_name' => $user?->name,
            'identification_number' => $user?->employee_code,
            'date_of_birth' => $user?->dob,
            'coaching_team' => $user?->group?->name,
            'work_unit' => $user?->department,
            'working_position' => $user?->position,
            'agree' => 'Xác nhận đúng thông tin cá nhân',
            'config_button' => [
                'text' => 'tiếp theo',
                'button_type' => 'button',
                'url' => Arr::get($attributes, 'next_link', '/'),
                'color_background' => '#324376',
                'detail_button_color_text' => '#ffffff',
                'icon' => [
                    'data' => '<svg width="13" height="20" viewBox="0 0 13 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.025 20L0.25 18.225L8.475 10L0.25 1.775L2.025 0L12.025 10L2.025 20Z" fill="white"/></svg>',
                ],
                'color_text' => '#ffffff',
            ],
        ];
    }
}
