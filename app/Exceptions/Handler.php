<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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

        $this->reportable(function (QueryException $e) {
            if ($e->getCode() === 23000) {
                $message = 'Foreign key constraint failed';
            } else {
                $message = $e->getMessage();
            }
        
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'message' => $e->getMessage(),
                ])
                ->with('info', $message); // Fixed this line
        });
    }
}
