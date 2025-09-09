<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Redirect 404, 419, 403 errors to / (dashboard) with a message
        if ($this->isHttpException($exception)) {
            $status = $exception->getStatusCode();
            if ($status === 419) {
                \Auth::logout();
                \Session::flush();
                return redirect('/')->with('error', 'You have been logged out due to inactivity or session expired.');
            }
            if (in_array($status, [404, 403])) {
                return redirect('/')->with('error', 'You were redirected because the page was not found, expired, or you do not have access.');
            }
        }
        return parent::render($request, $exception);
    }
} 