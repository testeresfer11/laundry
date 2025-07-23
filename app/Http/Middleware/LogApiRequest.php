<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $method = $request->getMethod();
        $uri = $request->getPathInfo();
        $headers = $request->headers->all();
        $params = $request->all();
        $language = $request->header('Accept-Language', 'not set');
        $ip = $request->ip();
        $userId = auth('sanctum')->id(); // null if unauthenticated

        // Log request details
        Log::info('[API Hit]', [
            'user_id' => $userId,
            'ip' => $ip,
            'method' => $method,
            'uri' => $uri,
            'accept_language' => $language,
            'params' => $params,
        ]);
        
        return $next($request);
    }
}
