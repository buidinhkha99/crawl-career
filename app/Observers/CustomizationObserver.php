<?php

namespace App\Observers;

use App\Models\Customization;
use Illuminate\Support\Facades\Cache;

class CustomizationObserver
{
    /**
     * Handle the Customization "created" event.
     *
     * @return void
     */
    public function created(Customization $customization)
    {
        //
    }

    /**
     * Handle the Customization "updated" event.
     *
     * @return void
     */
    public function updated(Customization $customization)
    {
        Cache::tags(['customizations'])->flush();
    }

    /**
     * Handle the Customization "deleted" event.
     *
     * @return void
     */
    public function deleted(Customization $customization)
    {
        //
    }

    /**
     * Handle the Customization "restored" event.
     *
     * @return void
     */
    public function restored(Customization $customization)
    {
        //
    }

    /**
     * Handle the Customization "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Customization $customization)
    {
        //
    }
}
