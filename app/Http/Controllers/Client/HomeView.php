<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeView extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $landing = config('landing_page');
        $payload = compact('landing');
        return view('client.pages.index', $payload);
    }
}
