<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Show a file.
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param string $path The path string.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $path)
    {
        $image = Storage::get($path);
        $mime = Storage::mimeType($path);

        return response($image)->header('Content-Type', $mime);
    }
}