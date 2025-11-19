<?php

namespace App\Http\Middleware;

use App\Enums\Admin\User\EnumRole;
use App\Services\UI\Toast;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StorageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth_check = Auth::check();
        $role_user = Auth::user()?->role;
        $is_allowed = in_array($role_user, EnumRole::list_all_role());
        if (!$auth_check || !$is_allowed) {
            Toast::info('Silahkan login terlebih dahulu.');
            return redirect()->route('client.homepage.index');
        }
        return $next($request);
    }
}
