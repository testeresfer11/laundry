<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['en', 'fr', 'es', 'ar', 'de', 'hi', 'id', 'it', 'nl', 'pt', 'ru', 'tl', 'ur']; // define your supported locales
        $acceptLang = $request->header('Accept-Language');

        if ($acceptLang) {
            $preferred = Str::substr($acceptLang, 0, 2); // crude parsing, or use a parser

            if (in_array($preferred, $supportedLocales)) {
                App::setLocale($preferred);
            }
        }

        return $next($request);
    }
}
