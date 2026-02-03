<?php

namespace App\Nova\Observer;

use App\Exceptions\AppException;
use App\Models\Form;
use App\Models\Lesson;
use Illuminate\Support\Arr;

class LessonObserver
{
    public function updated(Lesson $lesson)
    {
        if ($lesson->isDirty('content')) {
            $lesson->users()->newPivotStatement()->update([
                'complete_theory' => false,
                'is_complete' => false,
            ]);
        };
    }
}
