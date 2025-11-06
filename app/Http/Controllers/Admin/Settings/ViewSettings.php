<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\SukuBungaFlat;
use Illuminate\Http\Request;

class ViewSettings extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SukuBungaFlat $suku_bunga_model)
    {
        $breadcrumbs = [
            route('admin.index') => "Dashboard",
            null => "Pengaturan"
        ];
        $current_user = auth()->user()->first();
        $sukubunga = $suku_bunga_model->first();
        $payload = compact('breadcrumbs', 'current_user', 'sukubunga');
        return view('admin.pages.settings.view', $payload);
    }
}
