<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_user_group', 'user_group_id', 'user_id')->using(UserUserGroup::class);
    }

    public function getUsersCountAttribute()
    {
        return $this->attributes['users_count'] ?? $this->users()->count();
    }
}
