<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// This middleware is for when updating section, fields are not using default value; therefore some fields maybe null
class OverrideSectionEditModeWhenUpdate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (
            $request->route()->uri() !== 'nova-api/{resource}/{resourceId}/update-fields' ||
            $request->route()->parameter('resource') !== 'sections' ||
            $request->route()->parameter('resourceId') === null
        ) {
            return $next($request);
        }

        $query = $request->query;
        $query->set('editing', 'true');
        $query->set('editMode', 'create');

        $request->query = $query;

        return $next($request);
    }
}
