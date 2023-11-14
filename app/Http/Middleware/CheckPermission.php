<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\PermissionHelper::check($request)) {
            return $next($request);
        }

        //return response("Insufficient Permissions", 401);

        $status = 401;
        $message = 'Insufficient Permissions';
        return response(view(admin_view('layouts.error'), compact('status', 'message')), $status);
    }
}
