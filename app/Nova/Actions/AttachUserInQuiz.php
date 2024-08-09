<?php

namespace App\Nova\Actions;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class AttachUserInQuiz extends Action
{
    use InteractsWithQueue, Queueable;

    public function name(): string
    {
        return __('Attach User');
    }

    /**
     * Perform the action on the given models.
     *
     * @return \Laravel\Nova\Actions\ActionResponse|Action
     */
    public function handle(ActionFields $fields, Collection $models): Action|\Laravel\Nova\Actions\ActionResponse
    {
        if ($models->count() > 1) {
            return Action::danger(__('Can not action with multiple models'));
        }

        $models->each(function ($model) use ($fields) {
            $model->users()->attach($fields->user);
        });

        return Action::message(__('Add user to the quiz successfully!'));
    }

    /**
     * Get the fields available on the action.
     */
    public function fields(NovaRequest $request): array
    {
        $user_joined = Quiz::find($request->resources ?: $request->resourceId)->getAttribute('exam')?->getAttribute('users')->pluck('id');
        
        $users = User::whereNotIn('id', $user_joined)->get()->mapWithKeys(function ($item, $key) {
            return [$item->id => $item->name && $item->employee_code ? "$item->employee_code - $item->name" : ($item->employee_code ? $item->employee_code : $item->name)];
        });

        return [
            Select::make(__('Users'), 'user')
            ->options($users ?: [])
            ->searchable()
            ->rules('required'),
        ];
    }
}
