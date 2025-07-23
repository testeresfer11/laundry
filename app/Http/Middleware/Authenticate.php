<?php

namespace App\Http\Middleware;

use App\Traits\SendResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    use SendResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('api')->user();
        if($user){
            return $next($request);
        }else{
            return $this->apiResponse('error',401,config('constants.ERROR.AUTHORIZATION'));
        }
    }
}
