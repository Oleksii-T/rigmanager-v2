<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\JsonResponsable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use JsonResponsable;
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

    protected function unauthenticated($request, $exception)
    {
        activity('users')
            ->event('unauthenticated')
            ->withProperties(infoForActivityLog())
            ->log('');

        if ($request->expectsJson() || $request->ajax()) {
            return $this->jsonError(trans('messages.401'), 401);
        }

        flash('Please login to see the page.', false);

        return redirect()->route('login');
    }
}
