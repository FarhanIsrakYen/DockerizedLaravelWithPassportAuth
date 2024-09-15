<?php

use App\Http\Middleware\AuthorizationMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . "/../routes/web.php",
        // commands: __DIR__ . "/../routes/console.php",
        // health: "/up"
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api/',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api('throttle:api');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (RouteNotFoundException $exception) {
            return response()->json([
                'status' => 'Not an authenticated user',
                'message' => 'You need to login first',
                'error' => $exception->getMessage()
            ], ResponseAlias::HTTP_BAD_REQUEST);
        });
    })
    ->create();
