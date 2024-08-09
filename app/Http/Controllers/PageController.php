<?php

namespace App\Http\Controllers;

use App\Models\Customization;
use App\Models\FormSubmission;
use App\Models\PageStatic;
use App\Models\Setting;
use App\Rules\ValidRecaptcha;
use App\Traits\RenderSeoTrait;
use App\Traits\RenderSettingGlobalTrait;
use Artesaos\SEOTools\Facades\SEOTools;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PageController extends Controller
{
    use RenderSeoTrait, RenderSettingGlobalTrait;

    public function show(Request $request)
    {
        $default_lang = Setting::get('default_language');
        $lang = $request->session()->get('lang');

        $routeUri = $this->cut_prefix($request->route()->uri(), $request->route()->getPrefix());
        if (! Str::startsWith($routeUri, '/')) {
            $routeUri = "/$routeUri";
        }

        $path = $this->cut_prefix($request->path(), $request->route()->getPrefix());
        if (! Str::startsWith($path, '/')) {
            $path = "/$path";
        }

        if ($request->get('lang') == $default_lang) {
            return response()->redirectTo($path)->withCookie(cookie()->forever('lang', $request->get('lang')));
        }

        if ($request->get('lang')) {
            return response()->redirectTo("/{$request->get('lang')}{$path}")->withCookie(cookie()->forever('lang', $request->get('lang')));
        }

        if ($lang !== $default_lang && $request->route()->getPrefix() !== $lang) {
            return response()->redirectTo("/$lang$path")->withCookie(cookie()->forever('lang', $lang));
        }

        $match_pages = $request
            ->server('__pages__', PageStatic::enabled()->orderAsc()->get())
            ->filter(fn ($value, $key) => ($value->path === $path || $value->path === $routeUri));

        $page = $match_pages->firstWhere('language', $lang) ?? $match_pages->firstWhere('language', Setting::get('default_language')) ?? $match_pages->first();
        if (! $page) {
            abort(404, 'Page not found!');
        }

        if ($page->required_auth && ! Auth::check()) {
            if (PageStatic::where('path', '/login')->exists()) {
                return redirect('/login');
            }
            abort(403, 'Unauthorized action.');
        }

        $data = collect([
            'seo' => [
                'seo_title' => $page->seo_title,
                'seo_description' => $page->seo_description,
                'seo_keywords' => $page->seo_keywords,
                'seo_og_image_url' => $page->seo_og_image_url,
            ],
        ]);

        $render = $page->render($request, $lang);
        if ($render instanceof Response || $render instanceof RedirectResponse) {
            return $render;
        }

        $data = $data->merge($render);

        $this->setCommonSEO($data['seo']);
        $data->put('title', SEOTools::getTitle());

        app()->setLocale($lang);

        Inertia::share('lang', $lang);
        Inertia::share('setting', $this->getSettingGlobal());

        return Inertia::render('Page', $data)->withViewData([
            'h1' => $page->title,
        ])->toResponse($request)->withCookie(cookie()->forever('lang', $lang));
    }

    public function submit(Request $request)
    {
        try {
            request()->validate([
                'g-recaptcha-response' => ['required', new ValidRecaptcha],
                'form_id' => 'exists:forms,id',
            ]);

            $data = $request->all();
            unset($data['g-recaptcha-response']);
            unset($data['form_id']);

            FormSubmission::create([
                'author_id' => Auth::id(),
                'form_id' => $request->get('form_id'),
                'values' => $data,
            ]);

            return redirect()->back()->with('message', 'Thank you!');
        } catch (Exception $exception) {
            return redirect()->back()->withErrors(['message' => 'Contact error!']);
        }
    }

    public function subscribe(Request $request): RedirectResponse
    {
        request()->validate([
            'email' => ['required', 'email'],
        ]);
        try {
            Subscription::create([
                'email' => $request->get('email'),
            ]);

            return redirect()->back()->with('message', 'Thank you!');
        } catch (Exception $exception) {
            return redirect()->back()->withErrors(['message' => 'Subscribe error!']);
        }
    }

    public function customizePage(Request $request, $slug)
    {
        switch ($slug) {
            case 'custom.css':
                $content = Customization::get('CSS');

                return response($content, 200, [
                    'Content-Type' => 'text/css',
                ]);
            case 'custom.js':
                $content = Customization::get('Javascript');

                return response($content, 200, [
                    'Content-Type' => 'application/javascript',
                ]);
            default:
                return response('Page Not Found', 404);
        }
    }

    private function cut_prefix($str, $prefix)
    {
        if (substr($str, 0, strlen($prefix)) == $prefix) {
            $str = substr($str, strlen($prefix));
        }

        return $str;
    }
}
