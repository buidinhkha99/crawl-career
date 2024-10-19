<?php

namespace Database\Seeders;

use App\Enums\CertificateConstant;
use App\Jobs\CreateImageCertificate;
use App\Models\Certificate;
use Illuminate\Database\Seeder;

class ImageCertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $certificates = Certificate::where('type', CertificateConstant::OCCUPATIONAL_SAFETY)->get();
        foreach ($certificates as $certificate) {
            dispatch_sync(new CreateImageCertificate($certificate->id));
        }
    }
}
