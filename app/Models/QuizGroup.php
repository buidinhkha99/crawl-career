<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function quizzes()
    {
        return $this->hasMany(MockQuiz::class, 'group_id');
    }
}
