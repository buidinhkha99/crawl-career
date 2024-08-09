<?php

namespace App\Nova;

use Sereny\NovaPermissions\Nova\Permission as PermissionResource;

class Permission extends PermissionResource
{
    public static function label(): string
    {
        return __('Permissions');
    }

    public static $displayInNavigation = true;

    /**
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel()
    {
        return __('Create Permission');
    }
}
