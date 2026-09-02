<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;

class ImageUploadController extends Controller
{
    /**
     * Handle image upload from CKEditor 4.
     *
     * Guarda la imagen directamente en public/uploads/editor-images/ para evitar
     * problemas con el symlink de storage en hosting compartido.
     *
     * CKEditor 4 espera una respuesta HTML con:
     *   <script>window.parent.CKEDITOR.tools.callFunction(funcNum, url, errorMsg);</script>
     */
    public function upload(Request $request)
    {
        $funcNum = $request->query('CKEditorFuncNum', '0');

        try {
            if (!$request->hasFile('upload') || !$request->file('upload')->isValid()) {
                return $this->ckResponse($funcNum, '', 'No se recibió ningún archivo válido.');
            }

            $file = $request->file('upload');

            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, $allowed)) {
                return $this->ckResponse($funcNum, '', 'Tipo de archivo no permitido. Use: JPG, PNG, GIF o WEBP.');
            }

            if ($file->getSize() > 5 * 1024 * 1024) {
                return $this->ckResponse($funcNum, '', 'El archivo supera el tamaño máximo permitido (5 MB).');
            }

            // Guardar directamente en public/uploads/editor-images/ (sin symlink)
            $uploadDir = public_path('uploads/editor-images');
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filename = uniqid('img_') . '.' . $ext;
            $file->move($uploadDir, $filename);

            // URL pública directa
            $url = rtrim(config('app.url'), '/') . '/uploads/editor-images/' . $filename;

            return $this->ckResponse($funcNum, $url, '');

        } catch (Throwable $e) {
            return $this->ckResponse($funcNum, '', 'Error: ' . $e->getMessage());
        }
    }

    private function ckResponse(string $funcNum, string $url, string $errorMsg): \Illuminate\Http\Response
    {
        $url      = addslashes($url);
        $errorMsg = addslashes($errorMsg);

        return response(
            "<script>window.parent.CKEDITOR.tools.callFunction({$funcNum}, '{$url}', '{$errorMsg}');</script>"
        )->header('Content-Type', 'text/html');
    }
}
