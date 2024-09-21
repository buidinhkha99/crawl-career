<?php

namespace Database\Seeders;

use App\Enums\CertificateConstant;
use App\Jobs\CreateCertificate;
use App\Models\User;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::whereHas('groups')->get()->each(fn($user) => dispatch_sync(new CreateCertificate($user->id,CertificateConstant::OCCUPATIONAL_SAFETY, [
            'description' => 'An toàn, vệ sinh lao động dành cho người lao động thuộc nhóm ',
            'complete_from' => fake()->date,
            'complete_to' => fake()->date,
            'released_at' => fake()->date,
            'effective_to' => fake()->date,
        ])));
    }
}
