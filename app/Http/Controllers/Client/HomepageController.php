<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
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
    public function services()
    {
        return view('client.homepage.services');
    }
    public function profile()
    {
        return view('client.homepage.profile');
    }
    public function about_us()
    {
        return view('client.homepage.about-us');
    }
}
