<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'iot.api' => \App\Http\Middleware\VerifyIotApiKey::class,
            'query.profiler' => \App\Http\Middleware\QueryProfiler::class,
            'cache.response' => \App\Http\Middleware\CacheResponse::class,
        ]);

        // Apply query profiler globally to web and api routes
        $middleware->appendToGroup('web', \App\Http\Middleware\QueryProfiler::class);
        $middleware->appendToGroup('api', \App\Http\Middleware\QueryProfiler::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle authentication exceptions (401)
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->guest(route('login'))->with('error', 'Session Anda telah berakhir. Silakan login kembali.');
        });

        // Handle session errors gracefully
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 419) {
                return redirect()->back()->with('error', 'Halaman kadaluarsa. Silakan coba lagi.');
            }
        });

        // Handle 500 errors with user-friendly message
        $exceptions->renderable(function (\Throwable $e, $request) {
            if (app()->environment('production') && !$request->expectsJson()) {
                if ($e instanceof \ErrorException || $e instanceof \Error) {
                    \Log::error('Server Error 500', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'url' => $request->fullUrl(),
                        'user' => $request->user()?->id,
                    ]);
                    
                    return response()->view('errors.500', [], 500);
                }
            }
        });
    })->create();
