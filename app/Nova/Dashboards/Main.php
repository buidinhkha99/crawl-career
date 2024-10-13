<?php

namespace App\Nova\Dashboards;

use App\Nova\Filters\EndTimeFilter;
use App\Nova\Filters\StartTimeFilter;
use App\Nova\Metrics\Filterable\NumberExamsFilter;
use App\Nova\Metrics\Filterable\NumberQuestionsFilter;
use App\Nova\Metrics\Filterable\NumberQuizzesFilter;
use App\Nova\Metrics\Filterable\NumberSubmittedExaminationFilter;
use App\Nova\Metrics\Filterable\NumberUsersFilter;
use App\Nova\Metrics\Filterable\PercentExaminationsFilter;
use App\Nova\Metrics\Filterable\PercentQuestionsInTopicFilter;
use App\Nova\Metrics\Filterable\PercentUsersInGroupFilter;
use App\Nova\Metrics\Filterable\TopExaminationInExamFilter;
use App\Nova\Metrics\Filterable\TopExaminationResultInExamFilter;
use App\Nova\Metrics\NumberDoMockQuiz;
use App\Nova\Metrics\NumberExams;
use App\Nova\Metrics\NumberLessons;
use App\Nova\Metrics\NumberMockQuizzes;
use App\Nova\Metrics\NumberQuestions;
use App\Nova\Metrics\NumberUsers;
use App\Nova\Metrics\PercentExaminations;
use App\Nova\Metrics\PercentQuestionsInTopic;
use App\Nova\Metrics\PercentUsersInGroup;
use App\Nova\Metrics\Review\NumberAnswerQuestions;
use App\Nova\Metrics\Review\NumberCompleteLessons;
use App\Nova\Metrics\Review\NumberExaminationMockQuizzes;
use App\Nova\Metrics\Review\NumberNewUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Nova\Dashboards\Main as Dashboard;
use Nemrutco\NovaGlobalFilter\NovaGlobalFilter;
use Salt\TitleDashboard\TitleDashboard;

class Main extends Dashboard
{
    public function name()
    {
        return __('Summary report');
    }

    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        if (Auth::user()) {
            $can_view_Examinations = (method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin()) || Auth::user()->hasPermissionTo('viewDashboardExaminations');
            $can_view_Review = (method_exists(Auth::user(), 'isSuperAdmin') && Auth::user()->isSuperAdmin()) || Auth::user()->hasPermissionTo('viewDashboardReview');
            return [
                (new TitleDashboard())->setTitle($can_view_Examinations && $can_view_Review ? __('Summary report') : (!$can_view_Examinations && $can_view_Review ? __('Review report') : "Báo cáo sát hạch")),
                (new NumberUsers())->canSee(fn() => $can_view_Examinations || $can_view_Review),
                (new NumberQuestions())->canSee(fn() => $can_view_Examinations || $can_view_Review),
                (new NumberExams())->canSee(fn() => $can_view_Examinations),
                (new NumberLessons())->canSee(fn() => $can_view_Review),
                (new PercentUsersInGroup())->width('1/3')->canSee(fn() => $can_view_Examinations || $can_view_Review),
                (new PercentQuestionsInTopic())->width('1/3')->canSee(fn() => $can_view_Examinations || $can_view_Review),
                (new PercentExaminations())->width('1/3')->canSee(fn() => $can_view_Examinations),
                (new NumberMockQuizzes())->width('1/3')->canSee(fn() => $can_view_Review),
                (new NumberDoMockQuiz())->width('1/3')->canSee(fn() => $can_view_Review),

                // filter
                (new NovaGlobalFilter([
                    (new StartTimeFilter),
                    (new EndTimeFilter),
                ]))->inline()->canSee(fn() => $can_view_Examinations || $can_view_Review),

                (new NumberUsersFilter())->width('1/3')->canSee(fn() => $can_view_Examinations || $can_view_Review),
                (new NumberQuestionsFilter())->width('1/3')->canSee(fn() => $can_view_Examinations || $can_view_Review),
                (new NumberExamsFilter())->width('1/3')->canSee(fn() => $can_view_Examinations),
                (new PercentUsersInGroupFilter())->width('1/3')->canSee(fn() => $can_view_Examinations),
                (new PercentQuestionsInTopicFilter())->width('1/3')->canSee(fn() => $can_view_Examinations),
                (new NumberQuizzesFilter())->width('1/3')->canSee(fn() => $can_view_Examinations),
                (new NumberSubmittedExaminationFilter())->canSee(fn() => $can_view_Examinations),
                (new TopExaminationInExamFilter())->width('2/3')->canSee(fn() => $can_view_Examinations),
                (new PercentExaminationsFilter())->width('1/3')->canSee(fn() => $can_view_Examinations),
                (new TopExaminationResultInExamFilter())->width('2/3')->canSee(fn() => $can_view_Examinations),

                // charts ldv
                (new NumberNewUsers())->canSee(fn() => $can_view_Review),
                (new NumberExaminationMockQuizzes())->canSee(fn() => $can_view_Review),
                (new NumberCompleteLessons())->canSee(fn() => $can_view_Review),
                (new NumberAnswerQuestions())->canSee(fn() => $can_view_Review),
            ];
        }

        return [];
    }

}
