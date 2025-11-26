<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function get_file(Request $request)
    {
        $filepath = $request->get('path');

        if (!Storage::disk()->exists($filepath)) {
            abort(404);
        }

        $file = Storage::disk()->path($filepath);

        return response()->file($file);
    }
}
