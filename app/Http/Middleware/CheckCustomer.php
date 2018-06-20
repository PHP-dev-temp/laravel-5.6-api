<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckCustomer
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $user = Auth::guard('api')->user();
        if (!$user){
            return($this->errorResponse('Unauthenticated!', 401));
        }

        return $next($request);
    }
}
