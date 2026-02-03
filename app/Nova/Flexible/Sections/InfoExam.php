<?php

namespace App\Nova\Flexible\Sections;

use App\Nova\Flexible\Components\Background;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class InfoExam extends Layout
{
    use ComponentRender;

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'info_exam';

    /**
     * The displayed title
     *
     * @var string
     */
    protected $title = 'Info Exam';

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
            'type' => 'InfoExam',
            'background' => self::backgroundRender(
                Arr::get($attributes, 'background_option', 'color'),
                Arr::get($attributes, 'background', '#000000')
            ),
            'avatar' => 'https://ps.w.org/user-avatar-reloaded/assets/icon-256x256.png?rev=2540745',
            'full_name' => $user?->name,
            'identification_number' => $user?->employee_code,
            'date_of_birth' => $user?->dob,
            'coaching_team' => $user?->group?->name,
            'work_unit' => $user?->department,
            'working_position' => $user?->position,
            'test_time' => [
                'title' => 'Kỳ Sát hạch Huấn luyện AT-VSLĐ',
                'examinations' => 'Đợt 2 - 2023',
                'start_at' => '2022-08-01T14:09:06.397Z',
                'end_at' => '2022-08-01T14:09:06.397Z',
            ],
            'info_company' => [
                'title' => 'Chi nhánh Luyện đồng Lào Cai - Vimico',
                'address' => 'Tân Hồng, Bát Xát, Lào Cai',
                'phone' => '0214 383 8886',
                'website' => 'https://vimico.vn/',
            ],

        ];
    }
}
