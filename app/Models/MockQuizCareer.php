<?php

namespace App\Models;

use App\Scopes\MockQuizCareerScope;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class MockQuizCareer extends BaseQuiz implements Sortable
{
    use SortableTrait;

    protected $table='quizzes';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new MockQuizCareerScope());
    }

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public function group()
    {
        return $this->belongsTo(QuizGroup::class, 'group_id');
    }
}
