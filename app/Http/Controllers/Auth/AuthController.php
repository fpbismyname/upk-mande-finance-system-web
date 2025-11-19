<?php
namespace App\Http\Controllers\Auth;

use App\Enums\Admin\User\EnumRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\UI\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function admin_login_page()
    {
        return view('admin.auth.login');
    }
    public function client_login_page()
    {
        return view('client.auth.login');
    }
    public function client_register_page()
    {
        return view('client.auth.register');
    }

    public function submit_login(LoginRequest $request, User $user_model)
    {
        $creds = $request->validated();

        $current_user = $user_model->where('email', $creds['email'])->first();

        if (Auth::attempt($creds)) {
            $request->session()->regenerate();
            if (in_array($current_user->role, EnumRole::list_client_role())) {
                Toast::success('Login berhasil.');
                return redirect()->route('client.dashboard.index');
            }
            if (in_array($current_user->role, EnumRole::list_admin_role())) {
                Toast::success('Login berhasil.');
                return redirect()->route('admin.dashboard.index');
            }
        }

        Toast::info('Email atau password salah.');
        return redirect()->back()->withInput();
    }
    public function submit_register_client(RegisterRequest $request, User $user_model)
    {
        $new_user = $request->validated();

        $current_user = $user_model->where('email', $new_user['email'])->first();

        if ($current_user) {
            Toast::success('Email sudah digunakan, silahkan cobalagi dengan email yang lain.');
            return redirect()->back();
        }

        $data_new_user = $request->only(['name', 'email', 'password']);

        $register_user = $user_model->create([
            ...$data_new_user,
            'role' => EnumRole::ANGGOTA
        ]);

        if ($register_user->wasRecentlyCreated) {
            Toast::success('Akun berhasil terdaftar. Silahkan login untuk mengakses layanan kami.');
            return redirect()->route('client.login');
        }

        Toast::info('Gagal mendaftarkan akun anda.');
        return redirect()->back()->withInput();
    }
    public function submit_logout(Request $request)
    {
        $user = Auth::user();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerate();
        Toast::success('Logout berhasil');
        if (in_array($user->role, EnumRole::list_admin_role())) {
            return redirect()->route('admin.login');
        }
        if (in_array($user->role, EnumRole::list_client_role())) {
            return redirect()->route('client.login');
        }
    }
}
