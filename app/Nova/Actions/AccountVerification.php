<?php

namespace App\Nova\Actions;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Notifications\NovaNotification;

class AccountVerification extends Action
{
    use InteractsWithQueue, Queueable;

    public function name()
    {
        return __('Account Verification');
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $models->each(function ($model) {
            if ($model->getAttribute('status')) {
                return;
            }

            $validator = Validator::make(
                [
                    'employee_code' => $model->getAttribute('employee_code'),
                    'name' => $model->getAttribute('name'),
                    'dob' => $model->getAttribute('dob'),
                    'gender' => $model->getAttribute('gender'),
                    'phone' => $model->getAttribute('phone'),
                    'cccd/cmnd' => $model->getAttribute('username'),
                ],
                [
                    'employee_code' => 'required',
                    'name' => 'required',
                    'dob' => 'required',
                    'gender' => 'required',
                    'phone' => 'required',
                    'cccd/cmnd' => 'required',
                ],
                [],
                [
                    'employee_code' => __('Employee Code'),
                    'name' => __('Name User'),
                    'dob' => __('Date Of Birth'),
                    'gender' => __('Gender'),
                    'phone' => __('Phone Number'),
                    'cccd/cmnd' => 'CCCD/CMND'
                ]
            );

            if ($validator->fails()) {
                Auth::user()->notify(
                    NovaNotification::make()
                        ->message(__("Account verification with employee code (:employee_code) error. Error: :error", [
                            'employee_code' => $model->getAttribute('employee_code'),
                            'error' => $validator->errors()->first(),
                        ]))
                        ->type('error')
                );

                return;
            }

            $model->setAttribute('status', true);
            $model->save();
        });

        return Action::message(__('User verification successful, check the message if any user failed.'));
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }
}
