<?php

namespace App\Http\Middleware;

use Closure;

class GZipMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($response->headers->get('content-type') !== 'application/json') {
            return $response;
        }

        $response->setContent(gzencode($response->content(), 9));
        $response->headers->set('Content-Encoding', 'gzip');

        return $response;
    }
}
