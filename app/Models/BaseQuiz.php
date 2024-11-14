<?php

namespace App\Models;

use App\Exceptions\AppException;
use App\Scopes\QuizScope;
use Exception;
use Harishdurga\LaravelQuiz\Database\Factories\QuizFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class BaseQuiz extends Model
{
    protected $table = 'quizzes';

    protected $relationExam = Exam::class;
    protected $relationExamination = Examination::class;

    use HasSlug, HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'negative_marking_settings' => 'json',
    ];

    const FIXED_NEGATIVE_TYPE = 'fixed';
    const PERCENTAGE_NEGATIVE_TYPE = 'percentage';

    public function getTable()
    {
        return config('laravel-quiz.table_names.quizzes');
    }

    /**
     * Backward compatibility of the attribute
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function title(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => $attributes['name'],
            set: fn ($value) => ['name' => $value],
        );
    }

    public function topics()
    {
        return $this->morphToMany(config('laravel-quiz.models.topic'), 'topicable');
    }

    public function attempts()
    {
        return $this->hasMany(config('laravel-quiz.models.quiz_attempt'));
    }

    public static function newFactory()
    {
        return QuizFactory::new();
    }

    /**
     * Interact with the user's address.
     *
     * @return  \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function negativeMarkingSettings(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => empty($value) ? [
                'enable_negative_marks' => true,
                'negative_marking_type' => BaseQuiz::FIXED_NEGATIVE_TYPE,
                'negative_mark_value' => 0
            ] : json_decode($value, true),
        );
    }

    public function quizAuthors()
    {
        return $this->hasMany(config('laravel-quiz.models.quiz_author'));
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function exam(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo( $this->relationExam, 'exam_id');
    }

    public function questions(): \Illuminate\Database\Eloquent\Relations\HasMany|\Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'quiz_questions', 'quiz_id');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'quiz_attempts', 'quiz_id', 'participant_id')
            ->withPivotValue('participant_type', User::class);
    }

    public function examinations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany($this->relationExamination, 'quiz_id');
    }

    public function getKitAttribute(): Collection
    {
        return $this->questions()->get()
            ->groupBy('topic.name')->map(fn ($question, $key) => [
                'topics' => $key,
                'amount' => $question->count(),
            ])->values();
    }

    public function getUserCountAttribute(): int
    {
        return $this->users()->count();
    }

    public function getQuestionCountAttribute(): int
    {
        return $this->questions()->count();
    }

    public function getStatusAttribute(): string|null
    {
        return $this->getAttribute('exam')?->getAttribute('status');
    }

    public function getScorePassAttribute(): int
    {
        return $this->exam?->score_pass ?? 0;
    }

    /**
     * @throws Exception
     */
    public function saveKit(Collection $kits, $type = null, $question_amount_quiz = null)
    {
        $this->questions()->detach();
        $kits = collect($kits)->map(fn ($kit) => collect($kit)->values());
        if (!$type && !$question_amount_quiz && $kits->sum(fn ($kit) => (int) $kit[1] ?? 0) != $this->getAttribute('exam')->getAttribute('question_amount')) {
            throw new AppException(__("The quiz kit ':kit' must be equal to :question_amount questions", [
                'kit' => $this->getAttribute('name'),
                'question_amount' => $this->getAttribute('exam')->getAttribute('question_amount'),
            ]));
        }

        if ($type == 'quiz_review' && $question_amount_quiz && $kits->sum(fn ($kit) => (int) $kit[1] ?? 0) != $question_amount_quiz) {
            throw new AppException(__("The quiz kit ':kit' must be equal to :question_amount questions", [
                'kit' => $this->getAttribute('name'),
                'question_amount' => $question_amount_quiz,
            ]));
        }

        $kits->each(function ($kit) {
            $count_question = Topic::where('name', $kit[0])->first()?->questions()->count();

            if ($kit[1] > $count_question) {
                throw new AppException(__("In the kit ':kit', the topic ':topic' only :count_question questions", [
                    'kit' => $this->getAttribute('name'),
                    'topic' => $kit[0],
                    'count_question' => $count_question,
                ]));
            }

            $question_ids = Topic::where('name', $kit[0])->first()?->questions()->pluck('questions.id');

            $this->questions()->attach($question_ids->random($kit[1]));
        });
    }
}
