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
        User::whereHas('groups')->get()->each(fn($user) => dispatch_sync(new CreateCertificate($user->id,CertificateConstant::OCCUPATIONAL_SAFETY)));
    }
}
