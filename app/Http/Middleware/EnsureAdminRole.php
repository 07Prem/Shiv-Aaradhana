<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string[]  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active) {
            auth()->logout();
            return redirect()->route('admin.login')->withErrors(['email' => 'Account is inactive or not found.']);
        }

        // If no specific roles required, any authenticated active user passes
        if (empty($roles)) {
            return $next($request);
        }

        // Super admin has universal access
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has any of the requested roles
        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        abort(403, 'Unauthorized administrative action for role: ' . $user->role);
    }
}
