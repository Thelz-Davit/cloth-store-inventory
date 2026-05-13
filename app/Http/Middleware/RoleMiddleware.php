<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$allowedRoleNames)
    {
        if (!Auth::check()) {
            abort(403, 'Silakan login');
        }

        // $roleId = Auth::user()->role_id;

        // $roleName = DB::table('roles')
        //     ->where('role_id', $roleId)
        //     ->value('role_name');

        // if (!$roleName) {
        //     abort(403, 'Role tidak ditemukan');
        // }

        // if (strtolower($roleName) === 'superadmin') {
        //     return $next($request);
        // }

        // $roleNameLower = strtolower(trim($roleName));
        // $allowed = array_map(fn($r) => strtolower(trim($r)), $allowedRoleNames);

        // if (!in_array($roleNameLower, $allowed)) {
        //     abort(403, 'Tidak punya akses');
        // }

        return $next($request);
    }
}
