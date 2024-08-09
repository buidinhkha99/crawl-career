<?php

namespace App\Nova\Actions;

use App\Models\Setting;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outl1ne\MultiselectField\Multiselect;

class DownloadPDFReport extends Action
{
    public function name()
    {
        return __('Download PDF Report');
    }

    public function handle(ActionFields $fields, Collection $models): Action|\Laravel\Nova\Actions\ActionResponse
    {
        $payload = [
            'company_name' => Setting::get('company_name') ? $fields->get('company_name') : null,
            'place' => Setting::get('place') ? $fields->get('place') : null,
            'date_time' => Setting::get('date_time') ? $fields->get('date_time') : null,
            'title' => Setting::get('title') ? $fields->get('title') : null,
            'note' => Setting::get('note') ? $fields->get('note') : null,
            'verifier' => Setting::get('verifier') ? $fields->get('verifier') : null,
            'represent' => Setting::get('represent') ? $fields->get('represent') : null,
            'reporter' => Setting::get('reporter') ? $fields->get('reporter') : null,
            'headings' => collect(json_decode($fields->field_export)),
            'ids' => $models->pluck('id'),
        ];

        $hash = base64_encode(json_encode($payload));

        return Action::openInNewTab("/media/report?payload={$hash}");
    }

    public function fields(NovaRequest $request)
    {
        $company_name = Setting::get('company_name');
        $place = Setting::get('place');
        $date_time = Setting::get('date_time');
        $title = Setting::get('title');
        $note = Setting::get('note');
        $verifier = Setting::get('verifier');
        $reporter = Setting::get('reporter');
        $represent = Setting::get('represent');

        return collect([
            $company_name ? Text::make(__('Company Name'), 'company_name') : null,
            $place ? Text::make(__('Place'), 'place')->default(fn () => __('Lào Cai')) : null,
            $date_time ? Text::make(__('Time'), 'date_time')->default(fn () => __('date....., month...., year 2023')) : null,
            $title ? Text::make(__('Title'), 'title') : null,
            $note ? Text::make(__('Note'), 'note') : null,
            $verifier ? Text::make(__('Verifier title'), 'verifier') : null,
            $represent ? Text::make(__('Represent'), 'represent') : null,
            $reporter ? Text::make(__('Reporter title'), 'reporter') : null,
            Multiselect::make(__('Fields export'), 'field_export')
                ->options([
                    __('Employee Code') => __('Employee Code'),
                    __('Full Name') => __('Full Name'),
                    __('Date Of Birth') => __('Date Of Birth'),
                    __('CCCD/CMND') => __('CCCD/CMND'),
                    __('Exam') => __('Exam'),
                    __('Quiz') => __('Quiz'),
                    __('Score') => __('Score'),
                    __('Result') => __('Result'),
                    __('Duration') => __('Duration'),
                    __('Exam date') => __('Exam date'),
                    __('Gender') => __('Gender'),
                    __('Position ') => __('Position '),
                    __('Department') => __('Department'),
                    __('Factory') => __('Factory'),
                    __('Start Time') => __('Start Time'),
                    __('End Time') => __('End Time'),
                    __('Number Correct Answer') => __('Number Correct Answer'),
                    __('Number wrong answer') => __('Number wrong answer'),
                    __('Number Unanswered') => __('Number Unanswered'),
                    __('Signature') => __('Signature'),
                ])
                ->default(fn () => [
                    __('Employee Code'),
                    __('Full Name'),
                    __('Date Of Birth'),
                    __('CCCD/CMND'),
                    __('Exam'),
                    __('Quiz'),
                    __('Score'),
                    __('Result'),
                    __('Duration'),
                    __('Exam date'),
                    __('Signature')
                ])
                ->rules('required'),
        ])->filter();
    }
}
