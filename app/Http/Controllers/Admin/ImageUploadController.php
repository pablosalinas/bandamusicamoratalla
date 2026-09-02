<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ImageUploadController extends Controller
{
    /**
     * Handle image upload from CKEditor 4.
     *
     * CKEditor 4 espera una respuesta HTML con:
     *   <script>window.parent.CKEDITOR.tools.callFunction(funcNum, url, errorMsg);</script>
     * Si errorMsg no está vacío, CKEditor muestra el error y NO inserta la imagen.
     */
    public function upload(Request $request)
    {
        $funcNum = $request->query('CKEditorFuncNum', '0');

        try {
            // Validar el archivo
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

            $path = $file->store('editor-images', 'public');

            if (!$path) {
                return $this->ckResponse($funcNum, '', 'No se pudo guardar el archivo en el servidor.');
            }

            // URL absoluta usando APP_URL para garantizar que funciona en producción
            $url = rtrim(config('app.url'), '/') . '/storage/' . $path;

            return $this->ckResponse($funcNum, $url, '');

        } catch (Throwable $e) {
            return $this->ckResponse($funcNum, '', 'Error del servidor: ' . $e->getMessage());
        }
    }

    /**
     * Devuelve la respuesta HTML que espera CKEditor 4.
     */
    private function ckResponse(string $funcNum, string $url, string $errorMsg): \Illuminate\Http\Response
    {
        $url      = addslashes($url);
        $errorMsg = addslashes($errorMsg);

        return response(
            "<script>window.parent.CKEDITOR.tools.callFunction({$funcNum}, '{$url}', '{$errorMsg}');</script>"
        )->header('Content-Type', 'text/html');
    }
}
