<?php

namespace App\Models;

class QuestionOption extends \Harishdurga\LaravelQuiz\Models\QuestionOption
{
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'media_url',
        'media_type',
        'question_id'
    ];
    
    /**
     * Indicates if the model is currently force deleting.
     *
     * @var bool
     */
    protected $forceDeleting = true;
}
