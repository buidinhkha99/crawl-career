<?php

use App\Models\Certificate;

if (!function_exists('getNextNumberCardID')) {
    /**
     * Get error code
     * @param string $type
     * @return string
     */
    function getNextNumberCardID(string $type): string
    {
        return Certificate::where('type', $type)->max('card_id') + 1;
    }
}
