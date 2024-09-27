<?php

namespace Database\Seeders;

use App\Enums\CertificateConstant;
use App\Models\Certificate;
use App\Models\ObjectGroup;
use App\Models\UserGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravel\Octane\Exceptions\DdException;

class ObjectGroupCertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws DdException
     */
    public function run()
    {
       $certificates = Certificate::where('type', CertificateConstant::PAPER_SAFETY)->get();
       foreach ($certificates as $certificate) {
           $groupName = $certificate->card_info['group'] ?? null;
           if ($groupName && $group = UserGroup::where('name', $groupName)->first()) {
               ObjectGroup::firstOrCreate([
                   'name' => $groupName
               ], [
                   'name' => $groupName,
                   'description' => $group->description
               ]);
           }
       }
    }
}
