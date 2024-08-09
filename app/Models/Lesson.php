<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Outl1ne\NovaMediaHub\Models\Media;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'content', 'document'];
    protected $casts = [
        'document' => 'array'
    ];

    protected $hidden = ['document', 'created_at', 'updated_at', 'pivot'];

    protected $appends = ['documents'];

    public function topics()
    {
        return $this->morphToMany(config('laravel-quiz.models.topic'), 'topicable');
    }

    public function getDocumentsAttribute(): array
    {
        return !$this->getAttribute('document') || count($this->getAttribute('document')) === 0 ? [] :
            array_map(fn($doc) => Media::find($doc)?->getAttribute('url'), $this->getAttribute('document'));
    }

    public function getTopicAttribute()
    {
        return $this->topics()->first();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->using(LessonUser::class)->withPivot('complete_theory');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class)->using(LessonQuestion::class);
    }

    public function histories(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_user_question')->withPivot(['question_option_id', 'is_correct', 'question_id']);
    }
}
