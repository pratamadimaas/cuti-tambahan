<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $userRole = auth()->user()->role;

        if ($userRole === 'sekre') {
            if (!$request->routeIs('admin.buku-tamu.*') &&
                !$request->routeIs('dashboard') &&
                !$request->routeIs('logout')) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        }

        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}