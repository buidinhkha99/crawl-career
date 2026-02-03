<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class QuestionUser extends Pivot
{
    protected $table = 'question_user';

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(QuestionOption::class, 'question_option_id');
    }

    public function getIsCorrectAttribute(): bool
    {
        $answers = $this->question()->with('options')->first()->answers;
        return (bool)$answers->where('id', $this->question_option_id)->first()['is_correct'] ?? false;
    }
}
