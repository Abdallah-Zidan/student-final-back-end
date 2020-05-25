<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadsController extends Controller
{
    public function show($path)
    {
        $image = Storage::get($path);
        $mime = Storage::mimeType($path);
        return response($image)->header('Content-Type', $mime);
    }
}
