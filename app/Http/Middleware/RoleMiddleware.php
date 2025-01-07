<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {

        // //Check if the user is authenticated and is an admin
        // if (auth()->check() && auth()->user()->role === 'admin') {
        //     return $next($request);
        // }

        // // If not authorized, return a 403 response
        // abort(403, 'Unauthorized action.');
        // Check if the user is authenticated and has the 'admin' role

        if (Auth::check() && Auth::user()->role->name === $role) {

            return $next($request);
        }

        // Redirect or return an error response if not an admin
        return redirect()->route('admin.login')->with('error', 'You do not have admin access.');
    }
}
