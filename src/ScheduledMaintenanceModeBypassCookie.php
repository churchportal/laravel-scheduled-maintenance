<?php

namespace Churchportal\ScheduledMaintenance;

use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Cookie;

class ScheduledMaintenanceModeBypassCookie
{
    public static function create(string $key)
    {
        $expiresAt = Carbon::now()->addHours(12);

        return new Cookie(config('scheduled-maintenance.bypass_cookie_name'), base64_encode(json_encode([
            'expires_at' => $expiresAt->getTimestamp(),
            'mac' => hash_hmac('SHA256', $expiresAt->getTimestamp(), $key),
        ])), $expiresAt);
    }

    public static function isValid(string $cookie, string $key)
    {
        $payload = json_decode(base64_decode($cookie), true);

        return is_array($payload) &&
            is_numeric($payload['expires_at'] ?? null) &&
            isset($payload['mac']) &&
            hash_equals(hash_hmac('SHA256', $payload['expires_at'], $key), $payload['mac']) &&
            (int) $payload['expires_at'] >= Carbon::now()->getTimestamp();
    }

    public static function remove()
    {
        return new Cookie(config('scheduled-maintenance.bypass_cookie_name'), '', now()->subYears(10));
    }
}
