<?php

namespace App\Services\Admin\Auth;

use App\Enum\Admin\User\EnumRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public static function attempt(Request $request)
    {
        $creds = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Batasi untuk anggota dan tamu agar tidak bisa login
        $current_user = User::where('email', $creds['email'])->get();

        if ($current_user->isNotEmpty()) {
            $user_role = $current_user->first()->role;
            if ($user_role == EnumRole::ANGGOTA || $user_role == EnumRole::TAMU) {
                return false;
            }
        }

        if (Auth::attempt($creds)) {
            $request->session()->regenerate();
            return true;
        } else {
            return false;
        }
    }
}