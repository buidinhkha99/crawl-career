<?php

namespace App\Observers;

use App\Models\UserUserGroup;

class UserUserGroupObserver
{
    public function saving(UserUserGroup $user_user_group)
    {
        $user = $user_user_group->user;;
        if (empty($user->group) || $user->group->id != $user_user_group->user_group_id)
        {
            $user->examinations()->update([
                'group' => $user_user_group->group?->name,
            ]);
        }
    }
}
