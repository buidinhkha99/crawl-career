<?php

namespace App\Http\Middleware;

use App\Http\Controllers\PageController;
use App\Models\PageStatic;
use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoadStaticPageRoutes
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
            Str::startsWith("/{$request->path()}", config('nova.path')) ||
            Str::startsWith("/{$request->path()}", '/nova-api') ||
            Str::startsWith("/{$request->path()}", '/vendor')
        ) {
            return $next($request);
        }

        $router = app()->make('router');

        $pages = PageStatic::enabled()->orderAsc()->get();
        $languages = Setting::get('languages');

        foreach ($pages as $page) {
            foreach ($languages as $lang) {
                $lang = (object) $lang;

                if ($lang->default === 1) {
                    $router->get("/$page->path", [PageController::class, 'show'])->middleware('web');
                }

                $router->prefix($lang->key)->get("$page->path", [PageController::class, 'show'])->middleware('web');
            }
        }

        $request->server->set('__pages__', $pages);

        return $next($request);
    }
}
