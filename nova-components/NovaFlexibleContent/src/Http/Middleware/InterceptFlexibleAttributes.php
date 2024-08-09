<?php

namespace Salt\NovaFlexibleContent\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Salt\NovaFlexibleContent\Http\FlexibleAttribute;
use Salt\NovaFlexibleContent\Http\ParsesFlexibleAttributes;
use Salt\NovaFlexibleContent\Http\TransformsFlexibleErrors;
use Symfony\Component\HttpFoundation\Response;

class InterceptFlexibleAttributes
{
    use ParsesFlexibleAttributes;
    use TransformsFlexibleErrors;

    /**
     * Handle the given request and get the response.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->requestHasParsableFlexibleInputs($request)) {
            return $next($request);
        }

        $request->merge($this->getParsedFlexibleInputs($request));
        $request->request->remove(FlexibleAttribute::REGISTER);

        $response = $next($request);

        if (! $this->shouldTransformFlexibleErrors($response)) {
            return $response;
        }

        return $this->transformFlexibleErrors($response);
    }
}
