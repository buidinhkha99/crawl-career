<?php

namespace App\Http\Controllers;

use App\Enums\CertificateConstant;
use App\Enums\ExaminationStatus;
use App\Enums\UserGender;
use App\Models\Certificate;
use App\Models\Examination;
use App\Models\Setting;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Outl1ne\NovaMediaHub\Models\Media;
use Barryvdh\DomPDF\Facade\Pdf as BPDF;

class MediaController extends Controller
{
    public function streamExamPdf(Request $request, $id)
    {
        // check permissions
        if (! Auth::user()->can('viewAny', Examination::class)) {
            abort(403);
        }

        $exam = Examination::where('uuid', $id)->firstOrFail();
        $media = Media::find($exam->getAttribute('avatar'));
        $avatar = base64_encode(Storage::disk('public')->get($media?->path.$media?->file_name));

        if (! $avatar) {
            $avatar = base64_encode(Storage::disk('public')->get('default_avatar_user.png'));
        }
        $duration = $exam->getAttribute('duration') ?: 0;
        $hours = floor($duration / 3600);
        $minutes = floor(($duration / 60) % 60);
        $seconds = $duration % 60;
        $duration_convert = "$hours giờ, $minutes phút, $seconds giây";

        if ($hours == 0 && $minutes > 0) {
            $duration_convert = "$minutes phút, $seconds giây";
            if ($seconds == 0) {
                $duration_convert = "$minutes phút";
            }
        }

        if ($hours == 0 && $minutes == 0) {
            $duration_convert = "$seconds giây";
        }

        if ($hours > 0) {
            if ($seconds == 0) {
                $duration_convert = "$hours giờ, $minutes phút";
            }

            if ($minutes == 0) {
                $duration_convert = "$hours giờ";
            }
        }

        $html = Blade::render(Setting::get('exam_result_pdf'), [
            'created_at' => $exam->created_at->format('d/m/Y'),
            'quiz_name' => $exam->quiz_name,
            'exam_name' => $exam->getAttribute('exam_name'),
            'is_started_at' => $exam->getAttribute('start_time_exam')->format('d/m/Y'),
            'is_ended_at' => $exam->getAttribute('end_time_exam')->format('d/m/Y'),
            'duration' => $duration_convert,

            'user_info' => [
                'avatar' => 'data:image/png;base64,'.$avatar,
                'full_name' => $exam->getAttribute('name'),
                'identification_number' => $exam->getAttribute('employee_code'),
                'date_of_birth' => $exam->getAttribute('dob')?->format('d/m/Y'),
                'coaching_team' => $exam->getAttribute('group'),
                'work_unit' => $exam->getAttribute('department'),
                'working_position' => $exam->getAttribute('position'),
            ],
            'exam_result' => [
                'right_answers' => $exam->getAttribute('correct_answer'),
                'wrong_answers' => $exam->getAttribute('wrong_answer'),
                'unanswered' => $exam->getAttribute('unanswered'),
                'score' => $exam->getAttribute('score'),
                'is_passed' => $exam->getAttribute('state') == ExaminationStatus::Pass,
            ],
            'examination' => $exam->getAttribute('examination'),
        ], true);

        $filename = Str::snake("{$exam->exam_name}{$exam->name}_{$exam->username}").'.pdf';

        return PDF::loadHTML($html, 'UTF-8')->inline($filename);
    }

    public function streamReportPdf(Request $request)
    {
        // check permissions
        if (! Auth::user()?->can('viewAny', Examination::class)) {
            abort(403);
        }

        $hash = $request->get('payload');
        $payload = json_decode(base64_decode($hash));
        $payload->headings = collect($payload->headings);

        $html = Blade::render(Setting::get('content_page_pdf_report'), [
            'header' => [
                'company_name' => $payload?->company_name,
                'place' => $payload?->place,
                'date_time' => $payload?->date_time,
            ],
            'title' => $payload?->title,
            'table' => [
                'heading' => $payload?->headings ?? [],
                'data' => Examination::whereIn('id', $payload?->ids)->get()->map(function ($row) use ($payload) {
                    return $payload?->headings->map(function ($header) use ($row) {
                        return match ($header) {
                            __('Employee Code') => $row->employee_code,
                            __('Full Name') => $row->name,
                            __('Date Of Birth') => $row->dob?->format('d/m/Y'),
                            __('CCCD/CMND') => $row->username,
                            __('Exam') => $row->exam_name,
                            __('Quiz') => $row->quiz_name,
                            __('Score') => $row->score > 0 ? $row->score : '0',
                            __('Result') => $row->state,
                            __('Duration') => $row->duration ? gmdate('H:i:s', $row->duration) : null,
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
                            __('Signature') => null,
                        };
                    });
                })->toArray(),
            ],
            'note' => $payload?->note,
            'footer' => [
                'verifier' => $payload?->verifier,
                'reporter' => $payload?->reporter,
                'represent' => $payload?->represent
            ],
        ], true);

        $filename = 'bao_cao_bai_thi.pdf';

        return PDF::loadHTML($html, 'UTF-8')->setPaper('a4')->setOrientation('landscape')->inline($filename);
    }

    public function streamCertificatesPdf(Request $request)
    {
        // check permissions
        if (!Auth::user()) {
            abort(403);
        }

        $hash = session('payload');
        $payload = json_decode(base64_decode($hash));
        $type = $payload->type ?? null;

        if ($type == CertificateConstant::OCCUPATIONAL_SAFETY) {
            $pdf = $this->previewPDFOccuptionalCertificate($payload);

            // impotant can not change
            return $pdf->setPaper([0, 0, 595, 893])->setOption(['fontDir' => storage_path('/fonts')])->stream();
        }

        if ($type == CertificateConstant::ELECTRICAL_SAFETY) {
//            return $this->previewPDFElectricalCertificate($payload);
            $pdf = $this->previewPDFElectricalCertificate($payload);

            // impotant can not change
            return $pdf->setPaper([0, 0, 595, 893])->setOption(['fontDir' => storage_path('/fonts')])->stream();
        }

        abort(404);
    }

    private function previewPDFOccuptionalCertificate($payload)
    {
        $frontSizeCards = [];
        $backSizeCards = [];
        $certificates = Certificate::with('user')->whereIn('id', $payload->ids)->get()->reverse();
        foreach ($certificates as $cert) {
            $media = Media::find($cert->user->getAttribute('avatar'));
            $avatar = base64_encode(Storage::disk('public')->get($media?->path.$media?->file_name));
            $frontSizeCards[] = [
                'image' => $avatar,
                'certificate_id' => $cert->certificate_id,
            ];

            $backSizeCards[] = [
                'name' => $cert->user->name ?? null,
                'dob' => $cert->user->dob->format('d/m/Y') ?? null,
                'job' => $cert->job,
                'description' => $cert->card_info['description'] ?? null,
                'complete_from' => Carbon::parse($payload->complete_from)->format('d/m/Y'),
                'complete_to' => Carbon::parse($payload->complete_to)->format('d/m/Y'),
                'place' => $payload->place,
                'created_at' => Carbon::parse($cert->released_at)->format('d/m/Y'),
                'director_name' => $payload->director_name,
                'signature_photo' => $payload->signature_photo,
                'effective_to' => Carbon::parse($payload->effective_to)->format('d/m/Y'),
            ];
        }

        $groupFonts = collect($frontSizeCards)->chunk(9)->map(fn($group) => $group->values());
        $groupBacks = collect($backSizeCards)->chunk(9)->map(function($group) {
            $valueReversed = $group->chunk(3)->map(function ($groupCard) {
                $rowLose = 3 - $groupCard->count();
             if ($rowLose > 0) {
                 for ($i = 0; $i < $rowLose; $i++) {
                     $groupCard[] = [
                         'is_fake' => true,
                     ];
                 }
             };

             return $groupCard->reverse()->values();
            });

            return $valueReversed->collapse();
        });

        $dummyFilePath = resource_path('views/dummy.blade.php');
        // Write the html content to the blade file
        file_put_contents($dummyFilePath, Setting::get('pdf_occupational_certificate'));

        return BPDF::loadView('dummy', [
            'total_group' => $groupFonts->count(),
            'group_font_size_cards' => $groupFonts,
            'group_back_size_cards' => $groupBacks,
        ]);
    }

    private function previewPDFElectricalCertificate(mixed $payload)
    {
        $frontSizeCards = [];
        $backSizeCards = [];
        $certificates = Certificate::with('user')->whereIn('id', $payload->ids)->get()->reverse();
        foreach ($certificates as $cert) {
            $media = Media::find($cert->user->getAttribute('avatar'));
            $avatar = base64_encode(Storage::disk('public')->get($media?->path.$media?->file_name));
            $frontSizeCards[] = [
                'image' => $avatar,
                'certificate_id' => $cert->certificate_id,
            ];

            $backSizeCards[] = [
                'name' => $cert->user->name ?? null,
                'dob' => $cert->user->dob->format('d/m/Y') ?? null,
                'job' => $cert->job,
                'description' => $cert->card_info['description'] ?? null,
                'created_at' => Carbon::parse($cert->released_at)->format('d/m/Y'),
                'director_name' => $payload->director_name,
                'signature_photo' => $payload->signature_photo,
            ];
        }

        $groupFonts = collect($frontSizeCards)->chunk(9)->map(fn($group) => $group->values());
        $groupBacks = collect($backSizeCards)->chunk(9)->map(function($group) {
            $valueReversed = $group->chunk(3)->map(fn ($groupCard) => $groupCard->reverse()->values());

            return $valueReversed->collapse();
        });

        $dummyFilePath = resource_path('views/dummy.blade.php');
        // Write the html content to the blade file
        file_put_contents($dummyFilePath, Setting::get('pdf_electrical_certificate'));

        return BPDF::loadView('dummy', [
            'total_group' => $groupFonts->count(),
            'group_font_size_cards' => $groupFonts,
            'group_back_size_cards' => $groupBacks,
        ]);
    }
}
