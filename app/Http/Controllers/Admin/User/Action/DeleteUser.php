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
        $current_user = User::findOrFail($id);
        $delete_user = $user_service->deleteUser($id);
        if ($delete_user) {
            Toast::show(__('crud.delete_success', ['item' => $current_user->name]), 'success');
            return redirect()->route('admin.users.index');
        }
        Toast::show(__('crud.delete_failed', ['item' => $current_user->name]), 'error');
        return redirect()->back();
    }
}
