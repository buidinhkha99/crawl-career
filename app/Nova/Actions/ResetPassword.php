<?php

namespace App\Nova\Actions;

use Doctrine\DBAL\Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class ResetPassword extends Action
{
    use InteractsWithQueue, Queueable;

    public function name(): string
    {
        return __('Reset Password');
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return \Laravel\Nova\Actions\ActionResponse
     */
    public function handle(ActionFields $fields, Collection $models): \Laravel\Nova\Actions\ActionResponse
    {
        $models->each(function ($model) {
            $model->setAttribute('password', Hash::make($model->getAttribute('dob')?->format('dmY')));
            $model->save();
        });

        return Action::message(__('Password reset successful!'));
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [];
    }
}
