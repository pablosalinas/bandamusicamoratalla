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

            $width = $image->width();
            $height = $image->height();
            
            // Text to apply
            $text = 'www.bandamusicamoratalla.com';

            $posX = max(10, $width - 15);
            $posY = max(10, $height - 12);

            // Sombra oscura primero para legibilidad en fondos claros
            $image->text($text, $posX + 1, $posY + 1, function($font) {
                $font->file(5);
                $font->color('rgba(0, 0, 0, 0.45)');
                $font->align('right');
                $font->valign('bottom');
            });

            // Texto blanco discreto encima
            $image->text($text, $posX, $posY, function($font) {
                $font->file(5); 
                $font->color('rgba(255, 255, 255, 0.75)');
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
