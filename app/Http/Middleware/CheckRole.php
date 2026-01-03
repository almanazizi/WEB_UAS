<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            // Redirect to user's appropriate dashboard
            return $this->redirectToDashboard($userRole);
        }

        return $next($request);
    }

    private function redirectToDashboard($role)
    {
        return match($role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            'user' => redirect()->route('user.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
