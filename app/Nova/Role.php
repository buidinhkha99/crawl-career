<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Sereny\NovaPermissions\Fields\Checkboxes;
use Sereny\NovaPermissions\Nova\Role as RoleResource;

class Role extends RoleResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Role::class;

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query): \Illuminate\Database\Eloquent\Builder
    {
        if (!Auth::check() || Auth::user()?->isSuperAdmin()) return $query;

        $role_names = Auth::user()->getAttribute('roles')->map(fn ($role) =>
            $role->permissions()->where('group', 'Manager')->pluck('name')
        )->collapse();

        return $query->whereIn('name', $role_names);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request): array
    {
        $guardOptions = $this->guardOptions($request);
        $userResource = $this->userResource();

        return [
            ID::make(__('ID'), 'id')
                ->rules('required')
                ->canSee(function ($request) {
                    return $this->fieldAvailable('id');
                }),

            Text::make(__('Name'), 'name')
                ->rules(['required', 'string', 'max:255', 'regex:/^[a-z0-9A-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỀỂẾưăạảấầẩẫậắằẳẵặẹẻẽềềểếỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ\s]+$/'])
                ->creationRules('unique:' . config('permission.table_names.roles'))
                ->updateRules('unique:' . config('permission.table_names.roles') . ',name,{{resourceId}}'),

            Textarea::make(__('Description'), 'description')->nullable(),

            Select::make(__('Guard Name'), 'guard_name')
                ->options($guardOptions->toArray())
                ->rules(['required', Rule::in($guardOptions)])
                ->canSee(function ($request) {
                    return $this->fieldAvailable('guard_name');
                })
                ->default($this->defaultGuard($guardOptions)),

            Checkboxes::make(__('Permissions'), 'permissions')
                ->options($this->loadPermissions()->map(function ($permission, $key) {
                    return [
                        'group'  => __(ucfirst($permission->group)),
                        'option' => $permission->name,
                        'label'  => __($permission->name),
                    ];
                })
                    ->groupBy('group')
                    ->toArray())->canSee(fn() => Auth::user()->isSuperAdmin()),

            Text::make(__('Users'), function () {
                return $this->users()->count();
            })->exceptOnForms(),

            MorphToMany::make($userResource::label(), 'users', $userResource)
                ->searchable()
                ->canSee(function ($request) {
                    return $this->fieldAvailable('users');
                }),
        ];
    }

    public static function label(): string
    {
        return __('Roles');
    }

    public static $displayInNavigation = true;

    /**
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel(): ?string
    {
        return __('Create Role');
    }
}
