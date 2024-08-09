<?php

namespace App\Exceptions;

use App\Models\Setting;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if ($response->status() === 419) {
            return back()->with([
                'message' => __('The application has expired, please restart the application.'),
            ]);
        }

        if ($this->isHttpException($e)) {
            $error_page = Setting::get('error_pages');
            if (! empty($error_page) && $error_page->isNotEmpty()) {
                return response(Blade::render(Arr::get($error_page->where('status_code', $e->getStatusCode())->first(), 'content_page_error'), [
                    'status_code' => $e->getStatusCode(),
                    'message' => $e->getMessage(),
                ]), $e->getStatusCode());
            }

            $page = Setting::get('content_default_page_error');
            if (! $page) {
                return $response;
            }

            return response(Blade::render($page, [
                'status_code' => $e->getStatusCode(),
                'message' => $e->getMessage(),
            ]), $e->getStatusCode());
        }

        return $response;
    }
}
