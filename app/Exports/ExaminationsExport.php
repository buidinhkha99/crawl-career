<?php

namespace App\Exports;

use App\Enums\UserGender;
use App\Models\Examination;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExaminationsExport implements FromCollection, WithMapping
{
    /**
     * @return Collection
     */
    public collection $headings;

    public $ids;

    public function collection()
    {
        return collect([
            [
                ...$this->headings,
            ],
            ...Examination::whereIn('id', $this->ids)->get(),
        ]);
    }

    public function map($row): array
    {
        if (! $row instanceof Examination) {
            return $row;
        }

        return $this->headings->map(function ($header) use ($row) {
            return match ($header) {
                __('Employee Code') => $row->employee_code,
                __('Name') => $row->name,
                __('Date Of Birth') => $row->dob?->format('d/m/Y'),
                __('CCCD/CMND') => $row->username,
                __('Exam') => $row->exam_name,
                __('Quiz') => $row->quiz_name,
                __('Score') => $row->score > 0 ? $row->score : '0',
                __('Result') => $row->state,
                __('Duration') => gmdate('H:i:s', $row->duration),
                __('Exam date') => $row->start_time?->format('d/m/Y'),
                __('Gender') => UserGender::getValue($row->gender),
                __('Group User') => $row->group,
                __('Position ') => $row->position,
                __('Department') => $row->department,
                __('Factory') => $row->factory_name,
                __('Start Time') => $row->start_time?->format('d/m/Y H:i:s'),
                __('End Time') => $row->end_time?->format('d/m/Y H:i:s'),
                __('Number Correct Answer') => $row->correct_answer > 0 ? $row->correct_answer : '0',
                __('Number wrong answer') => $row->wrong_answer > 0 ? $row->wrong_answer : '0',
                __('Number Unanswered') => $row->unanswered > 0 ? $row->unanswered : '0',
            };
        })->toArray();
    }

    /**
     * @param  array  $headings
     */
    public function setHeadings(mixed $headings): static
    {
        $this->headings = $headings;

        return $this;
    }

    public function setIds(mixed $ids): static
    {
        $this->ids = $ids;

        return $this;
    }
}
