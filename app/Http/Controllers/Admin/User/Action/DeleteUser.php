<?php

namespace App\Http\Controllers\Admin\User\Action;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Admin\User\UserService;
use App\Services\UI\Toast;

class DeleteUser extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($id, UserService $user_service)
    {
        $result = $user_service->deleteUser($id);
        Toast::show($result->message, $result->type_message);
        return redirect()->route('admin.users.index');
    }
}
