<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffAuthorizationCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->roles_id == null) {
            return redirect()->route('intro')->with('notif', ['type' => 'warning', 'message' => 'Only SEEO Staff are alowed.']);
        }
        return $next($request);
    }
}
