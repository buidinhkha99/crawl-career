<?php

namespace App\Http\Controllers;

use App\Enums\CertificateConstant;
use App\Models\Certificate;
use Illuminate\Http\Request;


class CertificateController extends Controller
{
    public function certificates(Request $request)
    {
        $user = auth('api')->user();
        $certificateOccupation = Certificate::where('user_id', $user?->id)->where('type', CertificateConstant::OCCUPATIONAL_SAFETY)->orderBy('released_at', 'desc')->first();
        $certificateElectric = Certificate::where('user_id', $user?->id)->where('type', CertificateConstant::ELECTRICAL_SAFETY)->orderBy('released_at', 'desc')->first();
        $certificatePaper = Certificate::where('user_id', $user?->id)->where('type', CertificateConstant::PAPER_SAFETY)->orderBy('released_at', 'desc')->first();

        $certificates = [
            [
                'name' => 'Thẻ an toàn điện',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
  <path d="M11.251.068a.5.5 0 0 1 .227.58L9.677 6.5H13a.5.5 0 0 1 .364.843l-8 8.5a.5.5 0 0 1-.842-.49L6.323 9.5H3a.5.5 0 0 1-.364-.843l8-8.5a.5.5 0 0 1 .615-.09z"></path>
</svg>',
                'front' => $certificateElectric?->image_font_url,
                'back' => $certificateElectric?->image_back_url,
            ],
            [
                'name' => 'Thẻ ATLĐ',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
  <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"></path>
</svg>',
                'front' => $certificateOccupation?->image_font_url,
                'back' => $certificateOccupation?->image_back_url,
            ],
            [
                'name' => 'Giấy chứng nhận ATLĐ',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
  <path d="M15 15m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
  <path d="M13 17.5v4.5l2 -1.5l2 1.5v-4.5"></path>
  <path d="M10 19h-5a2 2 0 0 1 -2 -2v-10c0 -1.1 .9 -2 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -1 1.73"></path>
  <path d="M6 9l12 0"></path>
  <path d="M6 12l3 0"></path>
  <path d="M6 15l2 0"></path>
</svg>',
                'front' => $certificatePaper?->image_font_url,
                'back' => $certificatePaper?->image_back_url,
            ],
        ];

        return response()->json([
            'data' => $certificates
        ]);
    }
}
