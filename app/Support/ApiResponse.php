<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ApiResponse
{

    public static function login(User $user, string $token, string $token_type = 'Bearer', int $status = 200, string $code = 'success'): JsonResponse
    {
        return Response::json([
            'ok'            => true,
            'code'          => $code,
            'status_code'   => $status,
            'request_id'    => request()->attributes->get('request_id') ?? request()->header('X-Request-Id'),
            'timestamp'     => now()->toIso8601String(),
            'access_token'  => $token,
            'token_type'    => $token_type,
            'user'          => $user,
        ], $status);
    }

    public static function ok(mixed $data = [], int $status = 200, string $code = 'success'): JsonResponse
    {
        return Response::json([
            'ok'            => true,
            'code'          => $code,
            'status_code'   => $status,
            'request_id'    => request()->attributes->get('request_id') ?? request()->header('X-Request-Id'),
            'timestamp'     => now()->toIso8601String(),
            'data'          => $data,
        ], $status);
    }

    public static function fail(string $code, string $message, int $status = 400, array $extra = []): JsonResponse
    {
        return Response::json(array_merge([
            'ok'            => false,
            'code'          => $code,
            'status_code'   => $status,
            'message'       => $message,
            'request_id'    => request()->attributes->get('request_id') ?? request()->header('X-Request-Id'),
            'timestamp'     => now()->toIso8601String(),
        ], $extra), $status);
    }

}
