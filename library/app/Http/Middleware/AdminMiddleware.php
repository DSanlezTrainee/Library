<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated first
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Then check if the user is an admin
        if ($request->user()->isAdmin()) {
            return $next($request);
        }

        abort(403);
    }
}
