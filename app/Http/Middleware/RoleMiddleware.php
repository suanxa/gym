<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
{
    if (!Auth::check()) {
        return redirect('/login');
    }

    $role = Auth::user()->role;

    // Proteksi: Admin tidak boleh akses rute yang AWALAN-nya 'member'
    // (misal: /member/dashboard), tapi boleh akses /admin/members
    if ($role === 'admin' && $request->is('member*')) {
        return redirect()->route('admin.dashboard');
    }

    // Proteksi: Member tidak boleh akses rute yang AWALAN-nya 'admin'
    if ($role === 'member' && $request->is('admin*')) {
        return redirect()->route('member.dashboard');
    }

    return $next($request);
}
}