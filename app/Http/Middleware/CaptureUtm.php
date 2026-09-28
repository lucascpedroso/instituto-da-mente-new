<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guarda na sessão a origem da visita (UTM, gclid, fbclid) para anexar aos leads.
 * A primeira origem da sessão é preservada; uma nova campanha a substitui.
 */
class CaptureUtm
{
    public const KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'gclid', 'fbclid'];

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && $request->hasSession()) {
            $params = array_filter($request->only(self::KEYS));

            if ($params) {
                $request->session()->put('attribution', array_map(fn ($v) => mb_substr((string) $v, 0, 250), $params));
            }

            if (! $request->session()->has('landing_url')) {
                $request->session()->put('landing_url', mb_substr($request->fullUrl(), 0, 500));
            }
        }

        return $next($request);
    }
}
