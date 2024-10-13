<?php

namespace App\Jobs;

use App\Enums\CertificateConstant;
use App\Models\Certificate;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class CreateCertificate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected $userID, protected $type, protected $info)
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        try {
            $user = User::find($this->userID);
            if (empty($user)) {
                throw new Exception('User not found');
            }

            if (!in_array($this->type, CertificateConstant::LIST_TYPE_CERTIFICATE)) {
                throw new Exception('Type certificate not supported');
            }

            $dataCreate = match ($this->type) {
                CertificateConstant::OCCUPATIONAL_SAFETY => $this->getDataOccupation(),
                CertificateConstant::ELECTRICAL_SAFETY => $this->getDataElectrical(),
                CertificateConstant::PAPER_SAFETY => $this->getDataPaper(),
            };

            $certificate = Certificate::create($dataCreate);

            dispatch_sync(new CreateImageCertificate($certificate->id));
        } catch (Exception $e) {
            Log::error('[CERTIFICATE-SERVICE] Create certificate failed. UserID:' . $this->userID . ' Error: ' . $e->getMessage());

            throw new \Exception($e->getMessage());
        }
    }

    private function getDataOccupation(): array
    {
        $cardInfo = [
            'description' => $this->info['description'],
            'complete_from' => $this->info['complete_from'],
            'complete_to' => $this->info['complete_to'],
            'effective_to' => $this->info['effective_to'],
        ];

        return [
            'user_id' => $this->userID,
            'type' => $this->type,
            'card_info' => $cardInfo,
            'released_at' => $this->info['released_at'],
            'card_id' => $this->info['card_id'] ?? getNextNumberCardID($this->type, Carbon::parse($this->info['released_at'])->year),
            'place_printed' => Setting::get('place_occupational', "Lào Cai"),
            'director_name_printed' => Setting::get('director_name_occupational', "Họ và Tên"),
            'signature_photo_printed' => Setting::get('signature_photo_occupational'),
        ];
    }

    private function getDataElectrical(): array
    {
        $cardInfo = [
            'level' => $this->info['level'],
        ];

        return [
            'user_id' => $this->userID,
            'type' => $this->type,
            'card_info' => $cardInfo,
            'released_at' => $this->info['released_at'],
            'card_id' => $this->info['card_id'] ?? getNextNumberCardID($this->type, Carbon::parse($this->info['released_at'])->year),
            'director_name_printed' => Setting::get('director_name_electric', "Họ và Tên"),
            'signature_photo_printed' => Setting::get('signature_photo_electric', "Họ và Tên"),
        ];
    }

    private function getDataPaper(): array
    {
        $cardInfo = [
            'gender' => $this->info['gender'],
            'dob' => $this->info['dob'],
            'nationality' => $this->info['nationality'],
            'cccd' => $this->info['cccd'],
            'group' =>$this->info['group'],
            'result' => $this->info['result'],
            'complete_from' => $this->info['complete_from'],
            'complete_to' => $this->info['complete_to'],
            'effective_from' => $this->info['effective_from'],
            'effective_to' => $this->info['effective_to'],
        ];

        return [
            'user_id' => $this->userID,
            'type' => $this->type,
            'card_info' => $cardInfo,
            'released_at' => $this->info['released_at'],
            'card_id' => $this->info['card_id'] ?? getNextNumberCardID($this->type, Carbon::parse($this->info['released_at'])->year),
            'work_unit_printed' => Setting::get('work_unit', 'Chi nhánh luyện đồng lào cai'),
            'place_printed' => Setting::get('place_paper', "Lào Cai"),
            'director_name_printed' => Setting::get('director_name_paper', "Họ và Tên"),
            'signature_photo_printed' => Setting::get('signature_photo_paper'),
        ];
    }
}
