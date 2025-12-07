<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function get_public_file(Request $request)
    {
        $storage_public = Storage::disk('public');
        $filepath = $request->get('path');

        if (!$storage_public->exists($filepath)) {
            abort(404);
        }

        $file = $storage_public->path($filepath);

        return response()->file($file);
    }
    public function get_private_file(Request $request)
    {
        $storage_private = Storage::disk('local');
        $filepath = $request->get('path');

        if (!$storage_private->exists($filepath)) {
            abort(404);
        }

        $file = $storage_private->path($filepath);

        return response()->file($file);
    }
}
