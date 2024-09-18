<?php

namespace App\Jobs;

use App\Enums\CertificateConstant;
use App\Models\Certificate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
    public function __construct(protected $userID, protected $type)
    {

    }

    /**
     * Execute the job.
     *
     * @return void
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

            $cardInfo = match ($this->type) {
                CertificateConstant::OCCUPATIONAL_SAFETY => $this->getDataOccupation(),
                CertificateConstant::ELECTRICAL_SAFETY => $this->getDataElectrical($user),
            };

            // TODO: get data by import
            $releasedAt = fake()->date();
            Certificate::create([
                'user_id' => $this->userID,
                'type' => $this->type,
                'card_info' => $cardInfo,
                'released_at' => $releasedAt,
                'card_id' => getNextNumberCardID($this->type, Carbon::parse($releasedAt)->year)
            ]);
        } catch (Exception $e) {
            Log::error('[CERTIFICATE-SERVICE] Create certificate failed. UserID:' . $this->userID . ' Error: ' . $e->getMessage());
        }
    }

    private function getDataOccupation(): array
    {
        return [
            'description' => 'Đã hoàn thành khóa huấn luyện: An toàn, vệ sinh lao động dành cho người lao động thuộc nhóm 3',
            'complete_from' => fake()->date(),
            'complete_to' => fake()->date(),
            'effective_to' => fake()->date(),
        ];
    }

    private function getDataElectrical($user): array
    {
        return [];
    }
}
