<?php

namespace App\Nova\Observer;

use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleObserver
{
    public function updating(Role $role): void
    {
        if ($role->getOriginal('name') === $role->getAttribute('name')) return;

        $permission = Permission::where('name', $role->getOriginal('name'))->first();

        $permission->setAttribute('name', $role->getAttribute('name'));
        $permission->save();

        Artisan::call('permission:cache-reset');
    }

    public function creating(Role $role): void
    {
        $permission = Permission::create([
            'name' => $role->getAttribute('name'),
            'group' => 'Manager',
        ]);

        Role::findById(1)->givePermissionTo($permission);

        Artisan::call('permission:cache-reset');
    }

    public function deleting(Role $role): void
    {
        Permission::where('name', $role->getAttribute('name'))->delete();
        
        Artisan::call('permission:cache-reset');
    }
}
