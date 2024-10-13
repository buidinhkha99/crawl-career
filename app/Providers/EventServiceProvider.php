<?php

namespace App\Providers;

use App\Listeners\OctaneReloadPermissions;
use App\Models\Certificate;
use App\Models\Customization;
use App\Models\LessonQuestion;
use App\Models\QuestionOption;
use App\Models\QuizAttempt;
use App\Models\Section;
use App\Models\Setting;
use App\Observers\CertificateObserver;
use App\Observers\CustomizationObserver;
use App\Observers\LessonQuestionObserver;
use App\Observers\QuestionOptionObserver;
use App\Observers\QuizAttemptObserver;
use App\Observers\SectionObserver;
use App\Observers\SettingObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Laravel\Octane\Events\RequestReceived;
use Laravel\Octane\Events\TaskReceived;
use Laravel\Octane\Events\TickReceived;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        RequestReceived::class => [
            OctaneReloadPermissions::class,
        ],

        TaskReceived::class => [
            OctaneReloadPermissions::class,
        ],

        TickReceived::class => [
            OctaneReloadPermissions::class,
        ],
    ];

    protected $observers = [
        Setting::class => [SettingObserver::class],
        Section::class => [SectionObserver::class],
        Customization::class => [CustomizationObserver::class],
        QuestionOption::class => [QuestionOptionObserver::class],
        LessonQuestion::class => [LessonQuestionObserver::class],
        Certificate::class => [CertificateObserver::class],
        QuizAttempt::class => [QuizAttemptObserver::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
