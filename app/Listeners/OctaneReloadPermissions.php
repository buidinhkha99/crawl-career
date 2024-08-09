<?php

namespace App\Listeners;

use Spatie\Permission\PermissionRegistrar;

class OctaneReloadPermissions
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $event->sandbox->make(PermissionRegistrar::class)->clearClassPermissions();
    }
}
