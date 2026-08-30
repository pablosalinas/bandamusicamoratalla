<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageWatermarkService
{
    public static function applyWatermark($absolutePath)
    {
        try {
            // Check if file exists
            if (!file_exists($absolutePath)) {
                return false;
            }

            // Only process common image types
            $mime = mime_content_type($absolutePath);
            if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
                return true; // Not an image or not supported, ignore
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($absolutePath);

            // Determinar un tamaño de fuente dinámico basado en el ancho de la imagen
            $width = $image->width();
            
            // Text to apply
            $text = 'www.bandamusicamoratalla.com';

            // Usaremos la tipografía nativa 5 de GD que es la más grande, o dibujaremos varias veces para hacerla visible
            // Intervention v3
            $image->text($text, $width - 20, $image->height() - 20, function($font) {
                // $font->file(5); // GD internal fonts 1-5 (5 is largest)
                // Wait, if no TTF font is provided, v3 GD driver uses internal font 5.
                $font->file(5); 
                $font->color('rgba(255, 255, 255, 0.7)'); // 70% opacity white
                $font->align('right');
                $font->valign('bottom');
            });

            // Agregamos sombra o texto negro desfasado para asegurar visibilidad en fondos blancos
            $image->text($text, $width - 19, $image->height() - 19, function($font) {
                $font->file(5);
                $font->color('rgba(0, 0, 0, 0.5)'); // 50% opacity black
                $font->align('right');
                $font->valign('bottom');
            });

            // Sobrescribir la imagen
            $image->save($absolutePath);

            return true;
        } catch (\Exception $e) {
            \Log::error('Error aplicando marca de agua: ' . $e->getMessage());
            return false;
        }
    }
}
