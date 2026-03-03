<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\View;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            $this->logException($e);
        });
    }

    protected function logException(Throwable $e)
    {
        Log::error('Application error occurred', [
            'exception_class' => get_class($e),
            'message' => $this->sanitizeErrorMessage($e->getMessage()),
            'code' => $e->getCode(),
            'file' => basename($e->getFile()),
            'line' => $e->getLine(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'user_id' => auth()->id() ?? 'guest',
            'ip' => request()->ip(),
        ]);
    }

    protected function sanitizeErrorMessage($message)
    {
        $message = preg_replace('/\/[a-zA-Z0-9_\-\.]+\/[a-zA-Z0-9_\-\.]+/', '[PATH_REDACTED]', $message);
        $message = preg_replace('/s3\.amazonaws\.com\/[a-zA-Z0-9_\-]+/', 's3.amazonaws.com/[BUCKET_REDACTED]', $message);
        $message = preg_replace('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', '[IP_REDACTED]', $message);
        return $message;
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof AuthenticationException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'type' => 'Unauthorized',
                        'message' => 'Please login to access this resource.',
                        'code' => 401
                    ]
                ], 401);
            }
            return redirect()->guest(route('login'));
        }

        if ($request->expectsJson() || $request->is('api/*') || $request->is('api')) {
            return $this->renderJsonException($request, $e);
        }

        return $this->renderWebException($request, $e);
    }

    protected function renderJsonException(Request $request, Throwable $e)
    {
        $statusCode = 500;
        $errorType = 'Internal Server Error';
        
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            $errorType = $this->getErrorType($statusCode);
        } elseif ($e instanceof ValidationException) {
            $statusCode = 422;
            $errorType = 'Validation Error';
        }

        $response = [
            'success' => false,
            'error' => [
                'type' => $errorType,
                'message' => $this->getUserFriendlyMessage($statusCode),
                'code' => $statusCode,
            ],
        ];

        if (config('app.debug')) {
            $response['error']['debug'] = [
                'exception' => get_class($e),
                'message' => $this->sanitizeErrorMessage($e->getMessage()),
                'line' => $e->getLine(),
            ];
        }

        return new JsonResponse($response, $statusCode);
    }

    protected function renderWebException(Request $request, Throwable $e)
    {
        $statusCode = 500;
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
        }

        if ($statusCode === 401) {
            return redirect()->guest(route('login'));
        }

        $view = $this->getErrorView($statusCode);
        return response($view, $statusCode);
    }

    protected function getErrorView($statusCode)
    {
        // Try to find error view in theme
        $themeErrorPath = base_path('themes/Airdgereaders/resources/views/errors/' . $statusCode . '.blade.php');
        if (file_exists($themeErrorPath)) {
            try {
                return view()->file($themeErrorPath)->render();
            } catch (\Exception $e) {}
        }

        // Try main views/errors
        $mainErrorPath = resource_path('views/errors/' . $statusCode . '.blade.php');
        if (file_exists($mainErrorPath)) {
            try {
                return view()->file($mainErrorPath)->render();
            } catch (\Exception $e) {}
        }

        // Fallback to basic HTML
        return $this->getBasicErrorHtml($statusCode);
    }

    protected function getUserFriendlyMessage($statusCode)
    {
        $messages = [
            400 => 'The request was invalid. Please check your input and try again.',
            401 => 'Please login to access this resource.',
            403 => 'You do not have permission to access this page.',
            404 => 'The page you are looking for does not exist.',
            419 => 'Your session has expired. Please refresh the page and try again.',
            422 => 'The submitted data was invalid. Please check your input.',
            429 => 'Too many requests. Please wait a moment and try again.',
            500 => 'An unexpected error occurred. Please try again later.',
            503 => 'Service temporarily unavailable. Please try again later.',
        ];

        return isset($messages[$statusCode]) ? $messages[$statusCode] : 'An unexpected error occurred. Please try again later.';
    }

    protected function getErrorType($statusCode)
    {
        $types = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            419 => 'Session Expired',
            422 => 'Validation Error',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable',
        ];

        return isset($types[$statusCode]) ? $types[$statusCode] : 'Error';
    }

    protected function getBasicErrorHtml($statusCode, $title = null, $message = null)
    {
        $title = $title ?? $this->getErrorType($statusCode);
        $message = $message ?? $this->getUserFriendlyMessage($statusCode);
        
        $gradients = [
            403 => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            404 => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            500 => 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
        ];
        
        $gradient = $gradients[$statusCode] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
        
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error {$statusCode} - Project And Materials</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: {$gradient};
            color: #fff;
        }
        .error-container {
            text-align: center;
            padding: 50px;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            max-width: 600px;
        }
        .error-code {
            font-size: 100px;
            font-weight: bold;
            opacity: 0.3;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 32px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        p {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            padding: 15px 40px;
            background: #fff;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">{$statusCode}</div>
        <h1>{$title}</h1>
        <p>{$message}</p>
        <a href="/" class="btn">Return to Homepage</a>
    </div>
</body>
</html>
HTML;
    }

    public function shouldReport(Throwable $e)
    {
        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            return false;
        }
        
        return parent::shouldReport($e);
    }
}