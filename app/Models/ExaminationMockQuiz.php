<?php

namespace App\Models;

use App\Scopes\ExaminationMockQuizScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationMockQuiz extends Model
{
    use HasFactory;
    protected $table='examinations';
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new ExaminationMockQuizScope);
    }

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
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
