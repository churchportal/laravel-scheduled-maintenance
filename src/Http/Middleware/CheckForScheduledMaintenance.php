<?php

namespace Churchportal\ScheduledMaintenance\Http\Middleware;

use Churchportal\ScheduledMaintenance\ScheduledMaintenanceModeBypassCookie;
use Closure;
use Illuminate\Foundation\Http\MaintenanceModeBypassCookie;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance;

class CheckForScheduledMaintenance extends PreventRequestsDuringMaintenance
{
    public function handle($request, Closure $next)
    {
        if (app('maintenance')->isDown()) {
            $bypassSecret = app('maintenance')->current()->bypassSecret();
            $statusCode = app('maintenance')->current()->statusCode();

            if ($bypassSecret && $request->path() === $bypassSecret) {
                return $this->bypassResponse($bypassSecret);
            }

            $this->except = config('scheduled-maintenance.except', []);

            if ($this->hasValidBypassCookie($request, ['bypass_secret' => $bypassSecret]) || $this->inExceptArray($request)) {
                return $next($request);
            }

            $redirectTo = app('maintenance')->current()->redirectTo();

            if ($redirectTo) {
                $path = $redirectTo === '/' ? $redirectTo: trim($redirectTo, '/');

                if ($request->path() !== $path) {
                    return redirect($path)->setStatusCode($statusCode);
                }
            }

            return response(view(config('scheduled-maintenance.view'))->render())->setStatusCode($statusCode);
        } else if ($request->hasCookie(config('scheduled-maintenance.bypass_cookie_name'))) {
            $response = $next($request);

            return $response->withCookie(ScheduledMaintenanceModeBypassCookie::remove());
        }

        return $next($request);
    }

    protected function bypassResponse(string $secret)
    {
        return redirect('/')->withCookie(
            ScheduledMaintenanceModeBypassCookie::create($secret)
        );
    }

    protected function hasValidBypassCookie($request, array $data)
    {
        return isset($data['bypass_secret']) &&
            $request->cookie(config('scheduled-maintenance.bypass_cookie_name')) &&
            ScheduledMaintenanceModeBypassCookie::isValid(
                $request->cookie(config('scheduled-maintenance.bypass_cookie_name')),
                $data['bypass_secret']
            );
    }
}
