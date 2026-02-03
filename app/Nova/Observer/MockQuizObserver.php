<?php

namespace App\Nova\Observer;

use App\Models\MockQuiz;
use Illuminate\Support\Facades\Hash;

class MockQuizObserver
{
    public function deleted(MockQuiz $quiz): void
    {
        MockQuiz::setNewOrder(MockQuiz::select('id')->where('sort_order', '>', $quiz->sort_order)->get()->pluck('id'),  $quiz->sort_order);
    }
}
