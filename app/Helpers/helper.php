<?php

use App\Models\Certificate;

if (!function_exists('getNextNumberCardID')) {
    /**
     * Get error code
     * @param string $type
     * @return string
     */
    function getNextNumberCardID(string $type, $year): string
    {
        return Certificate::where('type', $type)->whereYear('released_at', $year)->max('card_id') + 1;
    }
}
