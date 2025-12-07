<?php

namespace App\Http\Middleware;

use App\Enums\Admin\User\EnumRole;
use App\Services\UI\Toast;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('client.login');
        }

        $user = Auth::user();

        // Cek role
        if (!in_array($user->role, EnumRole::list_client_role())) {
            return redirect()->route('client.login');
        }

        return $next($request);
    }
}
