<?php

namespace App\Models;

use App\Enums\CertificateConstant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'country',
        'company',
        'biography',
        'facebook',
        'linkedin',
        'github',
        'dob',
        'gender',
        'username',
        'position',
        'department',
        'factory_name',
        'avatar',
        'avatar_url',
        'employee_code',
        'status',
        'type'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'datetime',
    ];

    /**
     * Determines if the User is a Super admin
     *
     * @return null
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(Role::SUPER_ADMIN);
    }

    public function groups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(UserGroup::class, 'user_user_group', 'user_id', 'user_group_id')->using(UserUserGroup::class);
    }

    public function group()
    {
        $instance = $this->newRelatedInstance(UserGroup::class);

        return $this->newHasOne($instance->newQuery(), $this, 'user_user_group.user_id', $this->getKeyName())
            ->join('user_user_group', 'user_groups.id', '=', 'user_user_group.user_group_id')
            ->select('user_groups.*', 'user_user_group.user_id', 'user_user_group.user_group_id', 'user_user_group.user_group_id as id');
    }

    public function quizzes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Quiz::class, 'quiz_attempts', 'participant_id')
            ->withPivotValue('participant_type', User::class);
    }

    public function mockQuizzes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(MockQuiz::class, 'quiz_attempts', 'participant_id', 'quiz_id')
            ->withPivotValue('participant_type', User::class);
    }
    
    public function quizAttempts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        // TODO: check type quiz
        return $this->hasMany(QuizAttempt::class, 'participant_id');
    }


    public function examinations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Examination::class, 'user_id');
    }

    /**
     * Scope a query to only include popular users.
     */
    public function scopeManageRoles(Builder $query)
    {
        if (!Auth::check() || Auth::user()?->isSuperAdmin()) return;

        $role_names = Auth::user()->getAttribute('roles')->map(fn ($role) =>
            $role->permissions()->where('group', 'Manager')->pluck('name')
        )->collapse();

        $query->whereHas('roles', fn ($sub_query) =>
            $sub_query->whereIn('name', $role_names)
        )->orWhereDoesntHave('roles');
    }

    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class)->using(LessonUser::class)->withPivot(['complete_theory', 'is_complete']);
    }

    public function questions () {
        return $this->belongsToMany(Question::class, 'question_user')->using(QuestionUser::class)->withPivot('question_option_id');
    }

    public function lessonLearned(): BelongsToMany
    {
        return $this->lessons()->where('is_complete', true);
    }

    public function questionLearned () {
        return $this->questions()->wherePivot('is_correct', true);
    }


    public function findForPassport($username)
    {
        return $this->where('employee_code', $username)->first();
    }

    public function getRolesCountAttribute()
    {
        return $this->attributes['roles_count'] ?? $this->roles()->count();
    }

    public function certificate()
    {
        return $this->hasMany(Certificate::class);
    }

    public function occupationalCertificate()
    {
        return $this->certificate()->where('type', CertificateConstant::OCCUPATIONAL_SAFETY);
    }

    public function electricalCertificate()
    {
        return $this->certificate()->where('type', CertificateConstant::ELECTRICAL_SAFETY);
    }

    public function paperCertificate()
    {
        return $this->certificate()->where('type', CertificateConstant::PAPER_SAFETY);
    }
}
