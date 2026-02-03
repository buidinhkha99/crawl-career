<?php

namespace App\Nova;

use App\Nova\Traits\HasCallbacks;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use NovaAttachMany\AttachMany;

class UserGroup extends Resource
{
    use HasCallbacks;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\UserGroup>
     */
    public static $model = \App\Models\UserGroup::class;

    public static function label(): string
    {
        return __('Group User');
    }

    public static function singularLabel()
    {
        return __('Group User');
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    public static $displayInNavigation = true;

    public static $search = [
        'id', 'name',
    ];

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->withCount('users');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Name'), 'name')
                ->rules(['required', 'string', 'max:30', function($attribute, $value, $fail) {
                    if (!preg_match("/^[^,]+$/u", $value )) {
                        return $fail(__("Name without comma"));
                    }
                }])
                ->creationRules('unique:user_groups,name')
                ->updateRules('unique:user_groups,name,{{resourceId}}')
                ->sortable(),
            Textarea::make(__('Description'), 'description'),
            Text::make(__('Number Of Users'), 'users_count')->exceptOnForms()->sortable(),
            AttachMany::make(__('Users'), 'users', User::class)
                ->showCounts()
                ->showPreview(),
            BelongsToMany::make(__('Users'), 'users', User::class)->searchable(),
        ];
    }
}
