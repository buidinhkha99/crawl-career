<?php

namespace App\Nova\Actions;

use App\Models\UserGroup;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class AttachGroupUserInClass extends Action
{
    use InteractsWithQueue, Queueable;

    public function name(): string
    {
        return __('Attach Group User');
    }

    /**
     * Perform the action on the given models.
     */
    public function handle(ActionFields $fields, Collection $models): Action|\Laravel\Nova\Actions\ActionResponse
    {
        if ($models->count() > 1) {
            return Action::danger(__('Can not action with multiple models'));
        }

        $models->each(function ($model) use ($fields) {
            $user_joined = $model->attendees->pluck('id');
            $user_group = UserGroup::find($fields->user_groups)->users()->pluck('users.id');

            $user_group->each(function ($user) use ($user_joined, $model) {
                if ($user_joined->contains($user)) {
                    return;
                }

                $model->attendees()->attach($user);
            });
        });

        return Action::message(__('Add group user to the quiz successfully!'));
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Select::make(__('Group User'), 'user_groups')
            ->options(UserGroup::pluck('name', 'id'))
            ->searchable()->rules('required'),
        ];
    }
}
