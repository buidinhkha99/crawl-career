<?php

namespace App\Nova\Observer;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserObserver
{
    public function saving(User $user): void
    {
        if (array_key_exists('group_id', $user->getAttributes())) {
            unset($user->group_id);
        }

        if ($user->getAttribute('password') === null) {
            $user->setAttribute('password', Hash::make($user->getAttribute('dob')?->format('dmY')));
        }

    }

    public function saved(User $user): void
    {
       // update info user in exams done
        if ($user->isDirty('dob') ||
            $user->isDirty('username') ||
            $user->isDirty('gender') ||
            $user->isDirty('position') ||
            $user->isDirty('department') ||
            $user->isDirty('factory_name') ||
            $user->isDirty('name') ||
            $user->isDirty('avatar') ||
            $user->isDirty('employee_code')
        ) {
            $user->examinations()->update([
                'dob' => $user->getAttribute('dob'),
                'name' => $user->getAttribute('name'),
                'username' => $user->getAttribute('username'),
                'gender' => $user->getAttribute('gender'),
                'position' => $user->getAttribute('position'),
                'department' => $user->getAttribute('department'),
                'factory_name' => $user->getAttribute('factory_name'),
                'avatar' => $user->getAttribute('avatar'),
                'avatar_url' => $user->getAttribute('avatar_url'),
                'employee_code' => $user->getAttribute('employee_code'),
            ]);
        }
    }

    public function deleting(User $user): void
    {
        // delete tokens from user
        $user->tokens?->each(function($token, $key) {
            $token->delete();
        });

    }
}
