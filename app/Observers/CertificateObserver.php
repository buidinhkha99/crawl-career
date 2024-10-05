<?php

namespace App\Observers;

use App\Jobs\CreateImageCertificate;
use App\Models\Certificate;

class CertificateObserver
{
    /**
     * Handle the Certificate "created" event.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return void
     */
    public function created(Certificate $certificate)
    {
        //
    }

    /**
     * Handle the Certificate "updated" event.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return void
     */
    public function updated(Certificate $certificate)
    {
        if ($certificate->isDirty() && !$certificate->isDirty('image_font') && !$certificate->isDirty('image_back')) {
            dispatch_sync(new CreateImageCertificate($certificate->id));
        }
    }

    /**
     * Handle the Certificate "deleted" event.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return void
     */
    public function deleted(Certificate $certificate)
    {
        //
    }

    /**
     * Handle the Certificate "restored" event.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return void
     */
    public function restored(Certificate $certificate)
    {
        //
    }

    /**
     * Handle the Certificate "force deleted" event.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return void
     */
    public function forceDeleted(Certificate $certificate)
    {
        //
    }
}
