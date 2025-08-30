<?php

use App\Exceptions\InvalidCredentialsException;
use App\Http\Middleware\HandleRequestId;
use App\Http\Responses\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
//        web: __DIR__.'/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(HandleRequestId::class);
        $middleware->append(HandleCors::class);
        $middleware->appendToGroup('api', SubstituteBindings::class);
//        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        /*$middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);*/
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // 401 – Credenciales inválidas
        $exceptions->renderable(function (InvalidCredentialsException $e) {
            return ApiResponse::fail('invalid_credentials', __('errors.invalid_credentials'), 401);
        });

        // 401 – No autenticado
        $exceptions->renderable(function (AuthenticationException $e) {
            return ApiResponse::fail('unauthenticated', __('errors.unauthenticated'), 401);
        });

        // 403 – Prohibido
        $exceptions->renderable(function (AuthorizationException $e) {
            return ApiResponse::fail('forbidden', __('errors.forbidden'), 403);
        });

        // 404 – Recurso o ruta no encontrada
        $exceptions->renderable(function (NotFoundHttpException $e) {
            return ApiResponse::fail('not_found', __('errors.not_found'), 404);
        });

        // 405 – Método no permitido
        $exceptions->renderable(function (MethodNotAllowedHttpException $e) {
            return ApiResponse::fail('method_not_allowed', __('errors.method_not_allowed'), 405);
        });

        // 422 – Errores de validación (FormRequest o $request->validate)
        $exceptions->renderable(function (ValidationException $e) {
            return ApiResponse::fail('validation_error', __('errors.validation_error'), 422, [
                'errors' => $e->errors(),
            ]);
        });

        // 429 – Rate limit
        $exceptions->renderable(function (ThrottleRequestsException $e) {
            return ApiResponse::fail('rate_limited', __('errors.rate_limited'), 429, [
                'retry_after' => $e->getHeaders()['Retry-After'] ?? null,
            ]);
        });

        // 400/409/500 – Query/DB u otros
        $exceptions->renderable(function (QueryException $e) {
            // Puedes mapear violaciones UNIQUE a 409 (conflict)
            if (str_contains(strtolower($e->getMessage()), 'unique')) {
                return ApiResponse::fail('conflict', __('errors.conflict'), 409);
            }
            return ApiResponse::fail('database_error', __('errors.database_error'), 500);
        });

        // Fallback (cualquier otra excepción no manejada)
        $exceptions->renderable(function (\Throwable $e) {
            if (config('app.debug')) {
                // En DEBUG adjunta traza acotada (útil en dev)
                return ApiResponse::fail('server_error', __('errors.server_error'), 500, [
                    'trace' => collect($e->getTrace())->take(3), // evita respuestas enormes
                ]);
            }
            return ApiResponse::fail('server_error', __('errors.server_error'), 500);
        });
    })->create();
