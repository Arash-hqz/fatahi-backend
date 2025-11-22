<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CrosMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // For preflight requests return early with headers
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 204)
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Allow-Origin', $this->allowedOrigin($request))
                ->header('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PATCH, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Auth-Type, X-API-KEY, Origin, Content-Type, Accept, Authorization')
                ->header('Access-Control-Max-Age', '600');
        }

        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Credentials', 'true');
        $response->headers->set('Access-Control-Allow-Origin', $this->allowedOrigin($request));
        $response->headers->set('Access-Control-Allow-Methods', 'OPTIONS, GET, POST, PATCH, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Auth-Type, X-API-KEY, Origin, Content-Type, Accept, Authorization');
        return $response;
    }

    protected function allowedOrigin(Request $request): string
    {
        $origin = $request->headers->get('Origin');
        $allowed = config('app.cors_origin', '*');
        if ($allowed === '*') return '*';
        $list = array_map('trim', explode(',', $allowed));
        return in_array($origin, $list, true) ? $origin : $list[0];
    }
}
