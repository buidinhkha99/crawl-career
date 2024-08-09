<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserUserGroup extends Pivot
{
    protected $table = 'user_user_group';

    public $timestamps = null;

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserGroup::class, 'user_group_id');
    }
}
