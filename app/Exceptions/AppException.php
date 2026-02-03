<?php

namespace App\Exceptions;

use Illuminate\Http\Request;

class AppException extends \Exception
{
    public function render(Request $request): \Illuminate\Http\JsonResponse
    {
        if (! $request->wantsJson()) {
            return abort(422, $this->getMessage());
        }

        return response()->json([
            'message' => $this->getMessage(),
        ], 500);
    }
}
