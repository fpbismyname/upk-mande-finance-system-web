<?php

namespace App\Http\Controllers\Admin\Auth\Action;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SubmitLogout extends Controller
{
    public function __invoke()
    {
        Auth::logout();
        return redirect()->route('admin.login.index');
    }
}
