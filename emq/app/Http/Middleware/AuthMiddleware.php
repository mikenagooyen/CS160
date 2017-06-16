<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Admin;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     * Checks if user is admin
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user() == null){
            return redirect('home');
        }
        $admin = Admin::where('user_id', $request->user()->id)->exists();
        if(!$admin){
            return redirect('home');
        }
        return $next($request);
    }
}
