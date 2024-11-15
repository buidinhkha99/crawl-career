<?php

namespace App\Models;

use App\Scopes\ExaminationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseExamination extends Model
{
    use HasFactory;

    protected $table = 'examinations';
    protected string $relationQuiz = Quiz::class;
    protected string $relationExam = Exam::class;

    protected $casts = [
        'examination' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'dob' => 'datetime',
        'start_time_exam' => 'datetime',
        'end_time_exam' => 'datetime',
    ];

    protected $fillable = [
        'exam_id',
        'quiz_id',
        'user_id',
        'start_time',
        'end_time',
        'duration',
        'score',
        'state',
        'correct_answer',
        'wrong_answer',
        'unanswered',
        'examination',
        'dob',
        'username',
        'gender',
        'name',
        'position',
        'department',
        'factory_name',
        'exam_name',
        'start_time_exam',
        'end_time_exam',
        'uuid',
        'group',
        'quiz_name',
        'avatar_url',
        'avatar',
        'employee_code',
        'created_at',
        'type',
        'scope_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'exam_id');
    }

    public function exam()
    {
        return $this->belongsTo($this->relationExam, 'exam_id');
    }

    public function quiz()
    {
        return $this->belongsTo($this->relationQuiz, 'exam_id');
    }
}
