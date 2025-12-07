<?php

namespace App\Http\Controllers\Client;

use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $landing = config('landing_page');
        $payload = compact('landing');
        return view('client.homepage.index', $payload);
    }
    public function syarat_dan_ketentuan(Settings $settings_model)
    {
        $syarat_dan_ketentuan = $settings_model->getKeySetting(EnumSettingKeys::SYARAT_DAN_KETENTUAN)->value('value');
        $payload = compact('syarat_dan_ketentuan');
        return view('client.homepage.syarat-dan-ketentuan', $payload);
    }
}
