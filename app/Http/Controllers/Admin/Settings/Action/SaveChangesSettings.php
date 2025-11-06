<?php

namespace App\Http\Controllers\Admin\Settings\Action;

use App\Http\Controllers\Controller;
use App\Services\Admin\Settings\SettingsService;
use App\Services\UI\Toast;
use Illuminate\Http\Request;

class SaveChangesSettings extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, SettingsService $settings_service)
    {
        $type_settings = request()->get('type_settings');
        switch ($type_settings) {
            case 'informasi_akun':
                $new_entries = $request->validate([
                    'name' => 'required',
                    'email' => 'required',
                    'nomor_telepon' => 'required',
                    'reset_password' => '',
                    'new_password' => 'required_if:reset_password,on',
                    'min:6',
                ]);

                $is_reset_password = $request->input('reset_password') == 'on';

                $result = $settings_service->update_user($new_entries, $is_reset_password);

                Toast::show($result->message, $result->type_message);
                return redirect()->back();
            case 'suku_bunga':
                $new_entries = $request->validate([
                    'jumlah' => 'required',
                ]);
                $result = $settings_service->update_sukubunga($new_entries);
                Toast::show($result->message, $result->type_message);
                return redirect()->back();
        }
    }
}
