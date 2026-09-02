<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    /**
     * Handle image upload from CKEditor 4.
     * Expected response format: funcNum callback with JSON {uploaded, fileName, url, error}
     */
    public function upload(Request $request)
    {
        $funcNum = $request->query('CKEditorFuncNum', '');

        $request->validate([
            'upload' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ]);

        $file = $request->file('upload');
        $path = $file->store('editor-images', 'public');
        $url  = Storage::disk('public')->url($path);

        // CKEditor 4 espera una respuesta JavaScript con window.parent.CKEDITOR.tools.callFunction
        return response(
            "<script>window.parent.CKEDITOR.tools.callFunction({$funcNum}, '{$url}', '');</script>"
        )->header('Content-Type', 'text/html');
    }
}
