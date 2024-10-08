<?php

namespace App\Observers;

use App\Enums\CertificateConstant;
use App\Jobs\CacheSectionStructureByLocale;
use App\Jobs\CreateImageCertificate;
use App\Models\Certificate;
use App\Models\Section;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\AppException;
use App\Models\Topic;
use Illuminate\Support\Arr;

class SettingObserver
{
    public function saved(Setting $setting)
    {
        Cache::tags(['settings'])->flush();

        // if languages changed, cache all sections in every locale
        if ($setting->key === 'languages') {
            foreach (Section::all() as $section) {
                foreach (collect($setting->value)->pluck('key') as $locale) {
                    CacheSectionStructureByLocale::dispatch($section, $locale)->onQueue('default');
                }
            }
        }

        if (!empty($setting->getChanges()) && in_array($setting->key, ['place_occupational', 'complete_to', 'signature_photo_occupational', 'place_occupational', 'director_name_occupational', 'effective_to'])) {
            $certificateOccupations = Certificate::select('id')->where('type', CertificateConstant::OCCUPATIONAL_SAFETY)->get();
            foreach ($certificateOccupations as $certificate) {
                dispatch(new CreateImageCertificate($certificate->id))->onQueue('default');
            }
        }

        if (!empty($setting->getChanges()) && in_array($setting->key, ['director_name_electric', 'signature_photo_electric'])) {
            $certificateOccupations = Certificate::select('id')->where('type', CertificateConstant::ELECTRICAL_SAFETY)->get();
            foreach ($certificateOccupations as $certificate) {
                dispatch(new CreateImageCertificate($certificate->id))->onQueue('default');
            }
        }

        if (!empty($setting->getChanges()) && in_array($setting->key, ['work_unit', 'place_paper', 'director_name_paper', 'signature_photo_paper'])) {
            $certificateOccupations = Certificate::select('id')->where('type', CertificateConstant::PAPER_SAFETY)->get();
            foreach ($certificateOccupations as $certificate) {
                dispatch(new CreateImageCertificate($certificate->id))->onQueue('default');
            }
        }
    }
    public function deleted(Setting $setting)
    {
        Cache::tags(['settings'])->flush();
    }

    public function saving(Setting $setting, $type = null, $question_amount_quiz = null)
    {
        if ($setting->key === 'kit') {
            $kits = $setting->getAttribute('value');
            $question_amount = $setting->get('question_amount_quiz');
            if (!$type && !$question_amount_quiz && $setting->value->sum(fn ($kit) => (int) Arr::get($kit, 'amount') ?? 0) !=  $question_amount) {
                throw new AppException(__("The quiz kit must be equal to :question_amount questions", [
                    'question_amount' =>  $question_amount,
                ]));
            }

            $kits->each(function ($kit) {
                $count_question = Topic::where('name', $kit['topics'])->first()?->questions()->count();
                if ($kit['amount'] > $count_question) {
                    throw new AppException(__("In the kit, the topic ':topic' only :count_question questions", [
                        // 'kit' => $this->getAttribute('name'),
                        'topic' => $kit['topics'],
                        'count_question' => $count_question,
                    ]));
                }
            });
        }
    }
}
