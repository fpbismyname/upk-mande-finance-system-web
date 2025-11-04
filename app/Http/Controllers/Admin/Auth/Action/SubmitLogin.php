<?php

namespace App\Http\Controllers\Admin\Auth\Action;

use App\Http\Controllers\Controller;
use App\Services\Admin\Auth\LoginService;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class SubmitLogin extends Controller
{
    public function __invoke(Request $request, LoginService $login_service)
    {
        $attempt_login = $login_service->attempt($request);
        if ($attempt_login) {
            return redirect()->route('admin.index');
        } else {
            Toast::show('Email atau password yang anda masukan salah', 'error');
            return redirect()->back();
        }
    }
}
