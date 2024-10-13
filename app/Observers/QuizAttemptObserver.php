<?php

namespace App\Observers;

use App\Jobs\CreateImageCertificate;
use App\Models\Certificate;
use App\Models\QuizAttempt;

class QuizAttemptObserver
{
    /**
     * Handle the Certificate "created" event.
     *
     * @param QuizAttempt $certificate
     * @return void
     */
    public function created(QuizAttempt $certificate)
    {
        $certificate->is_pass = $certificate->state;
        $certificate->save();
    }

    /**
     * Handle the QuizAttempt "updated" event.
     *
     * @param QuizAttempt $certificate
     * @return void
     */
    public function updated(QuizAttempt $certificate)
    {
        if ($certificate->isDirty() && !$certificate->isDirty('is_pass')) {
            $certificate->is_pass = $certificate->state;
            $certificate->save();
        }
    }

    /**
     * Handle the QuizAttempt "deleted" event.
     *
     * @param QuizAttempt $certificate
     * @return void
     */
    public function deleted(QuizAttempt $certificate)
    {
        //
    }

    /**
     * Handle the QuizAttempt "restored" event.
     *
     * @param QuizAttempt $certificate
     * @return void
     */
    public function restored(QuizAttempt $certificate)
    {
        //
    }

    /**
     * Handle the QuizAttempt "force deleted" event.
     *
     * @param QuizAttempt $certificate
     * @return void
     */
    public function forceDeleted(QuizAttempt $certificate)
    {
        //
    }
}
