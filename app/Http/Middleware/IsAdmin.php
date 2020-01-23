<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      // return response(['user' => $request->user()]);
        if ($request->user()->role_id != 1) {
            return response([
                'status' => 'error',
                'message' => 'Unauthorized Admin.'
            ], 401);
        }
        return $next($request);
    }
}
