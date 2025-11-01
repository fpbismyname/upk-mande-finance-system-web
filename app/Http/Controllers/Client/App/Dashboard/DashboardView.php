<?php

namespace App\Http\Controllers\Client\App\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardView extends Controller
{
    public function __invoke(){
        return view("client.pages.index");
    }
}
