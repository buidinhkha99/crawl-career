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

            $cardInfo = match ($this->type) {
                CertificateConstant::OCCUPATIONAL_SAFETY => $this->getDataOccupation(),
                CertificateConstant::ELECTRICAL_SAFETY => $this->getDataElectrical(),
            };

            Certificate::create([
                'user_id' => $this->userID,
                'type' => $this->type,
                'card_info' => $cardInfo,
                'released_at' => $this->info['released_at'],
                'card_id' => $this->info['card_id'] ?? getNextNumberCardID($this->type, Carbon::parse($this->info['released_at'])->year)
            ]);
        } catch (Exception $e) {
            Log::error('[CERTIFICATE-SERVICE] Create certificate failed. UserID:' . $this->userID . ' Error: ' . $e->getMessage());

            throw new \Exception($e->getMessage());
        }
    }

    private function getDataOccupation(): array
    {
        return [
            'description' => $this->info['description'],
            'complete_from' => $this->info['complete_from'],
            'complete_to' => $this->info['complete_to'],
            'effective_to' => $this->info['effective_to'],
        ];
    }

    private function getDataElectrical(): array
    {
        return [
            'description' => $this->info['description'],
            'level' => $this->info['level'],
        ];
    }
}
