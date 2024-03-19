<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ImagemViewController extends Controller
{
    public function __invoke(string $path)
    {
        $content = Storage::disk('s3')->get($path);

        return response($content)->header('Content-Type', 'image/jpeg');
    }
}
