<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class HandleRequestId
{
    public function handle(Request $request, Closure $next)
    {
        $id = $request->header('X-Request-Id') ?: (string) Str::ulid();
        // Guarda en atributos para que tu handler lo pueda leer
        $request->attributes->set('request_id', $id);

        $response = $next($request);
        // Expón el mismo ID en la respuesta
        $response->headers->set('X-Request-Id', $id);
        return $response;
    }
}
