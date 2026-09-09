<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $bandIban = '';
        $rawIban = \App\Models\SiteSetting::getSetting('band_iban', '');
        if ($rawIban) {
            try {
                $bandIban = \Illuminate\Support\Facades\Crypt::decryptString($rawIban);
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                $bandIban = '';
            }
        }

        $backupPassword = '';
        if (auth()->user() && auth()->user()->isSuperAdmin()) {
            $rawBackupPass = \App\Models\SiteSetting::getSetting('backup_password', '');
            if ($rawBackupPass) {
                try {
                    $backupPassword = \Illuminate\Support\Facades\Crypt::decryptString($rawBackupPass);
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    $backupPassword = '';
                }
            } else {
                $backupPassword = '@Acemila2026'; // Default request
            }
        }

        $rawLogos = json_decode(\App\Models\SiteSetting::getSetting('site_logos', '[]'), true) ?: [];
        $logos = [];
        foreach ($rawLogos as $logo) {
            if (is_string($logo)) {
                $logos[] = ['path' => $logo, 'order' => 999];
            } else if (is_array($logo)) {
                $logos[] = $logo;
            }
        }
        usort($logos, function($a, $b) {
            return ($a['order'] ?? 999) <=> ($b['order'] ?? 999);
        });

        $settings = [
            'band_name' => \App\Models\SiteSetting::getSetting('band_name', 'Banda de Música de Moratalla'),
            'site_slogan' => \App\Models\SiteSetting::getSetting('site_slogan', 'Tu banda'),
            'session_timeout' => \App\Models\SiteSetting::getSetting('session_timeout', 120),
            'statutes' => \App\Models\SiteSetting::getSetting('statutes', ''),
            'band_history' => \App\Models\SiteSetting::getSetting('band_history', ''),
            'carousel_speed' => \App\Models\SiteSetting::getSetting('carousel_speed', 4),
            'band_iban' => $bandIban,
            'site_logos' => $logos,
            'parental_consent_template' => \App\Models\SiteSetting::getSetting('parental_consent_template', ''),
            'parental_consent_pdf' => \App\Models\SiteSetting::getSetting('parental_consent_pdf', ''),
        ];
        
        $carouselMedia = \App\Models\CarouselMedia::orderBy('sort_order')->get();
        $bandHistoryImages = \App\Models\BandHistoryImage::orderBy('sort_order')->get();
        
        return view('admin.settings.index', compact('settings', 'carouselMedia', 'bandHistoryImages', 'backupPassword'));
    }

    public function update(Request $request)
    {
        $rules = [
            'band_name' => 'required|string|max:255',
            'site_slogan' => 'nullable|string|max:255',
            'session_timeout' => 'required|integer|min:1',
            'statutes' => 'nullable|string',
            'band_history' => 'nullable|string',
            'carousel_speed' => 'required|integer|min:1',
            'parental_consent_template' => 'nullable|string',
            'parental_consent_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ];

        if (auth()->user()->canViewIban()) {
            
            $rules['band_iban'] = ['nullable', 'string', 'max:50', new \App\Rules\ValidIban];
        
        }
        
        if (auth()->user()->isSuperAdmin()) {
            $rules['backup_password'] = ['nullable', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            if ($key === 'band_iban') {
                
                $value = $value ? \Illuminate\Support\Facades\Crypt::encryptString($value) : '';
            
            }
            if ($key === 'backup_password' && auth()->user()->isSuperAdmin()) {
                $value = $value ? \Illuminate\Support\Facades\Crypt::encryptString($value) : '';
            }
            if ($key === 'parental_consent_pdf') {
                continue; // Handled below
            }
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => is_numeric($value) ? 'integer' : 'text']
            );
        }

        if ($request->hasFile('parental_consent_pdf')) {
            $path = $request->file('parental_consent_pdf')->store('settings', 'public');
            \App\Models\SiteSetting::updateOrCreate(
                ['key' => 'parental_consent_pdf'],
                ['value' => $path, 'type' => 'text']
            );
        }

        return redirect()->route('admin.settings.index')->with('success', 'Configuración actualizada correctamente.');
    }

    public function storeCarouselMedia(Request $request)
    {
        $request->validate([
            'media' => 'required|array',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:51200' // Max 50MB per file
        ]);

        if ($request->hasFile('media')) {
            $maxOrder = \App\Models\CarouselMedia::max('sort_order') ?? 0;
            
            foreach ($request->file('media') as $file) {
                $path = $file->store('carousel', 'public');
                $mime = $file->getMimeType();
                $type = str_starts_with($mime, 'video/') ? 'video' : 'image';
                $maxOrder++;
                \App\Models\CarouselMedia::create([
                    'file_path' => $path,
                    'type' => $type,
                    'sort_order' => $maxOrder
                ]);
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Archivos añadidos al carrusel correctamente.');
    }

    public function destroyCarouselMedia(\App\Models\CarouselMedia $media)
    {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($media->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($media->file_path);
        }
        $media->delete();
        
        return redirect()->route('admin.settings.index')->with('success', 'Archivo eliminado del carrusel.');
    }

    public function updateCarouselMedia(Request $request, \App\Models\CarouselMedia $media)
    {
        $request->validate([
            'description' => 'nullable|string|max:255',
        ]);
        
        $media->update([
            'description' => $request->description
        ]);
        
        return redirect()->route('admin.settings.index')->with('success', 'Descripción actualizada.');
    }

    public function storeLogo(Request $request)
    {
        $request->validate([
            'logos' => 'required|array',
            'logos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        $rawLogos = json_decode(\App\Models\SiteSetting::getSetting('site_logos', '[]'), true) ?: [];
        $currentLogos = [];
        $maxOrder = 0;
        foreach ($rawLogos as $logo) {
            if (is_string($logo)) {
                $currentLogos[] = ['path' => $logo, 'order' => 999];
            } else if (is_array($logo)) {
                $currentLogos[] = $logo;
                if (isset($logo['order']) && $logo['order'] > $maxOrder && $logo['order'] != 999) {
                    $maxOrder = $logo['order'];
                }
            }
        }

        if ($request->hasFile('logos')) {
            foreach ($request->file('logos') as $file) {
                $path = $file->store('logos', 'public');
                $maxOrder++;
                $currentLogos[] = ['path' => $path, 'order' => $maxOrder];
            }
        }

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'site_logos'],
            ['value' => json_encode(array_values($currentLogos)), 'type' => 'text']
        );

        return redirect()->route('admin.settings.index')->with('success', 'Logos añadidos correctamente.');
    }

    public function destroyLogo(Request $request)
    {
        $path = $request->input('path');
        $rawLogos = json_decode(\App\Models\SiteSetting::getSetting('site_logos', '[]'), true) ?: [];
        $currentLogos = [];
        foreach ($rawLogos as $logo) {
            $logoPath = is_string($logo) ? $logo : ($logo['path'] ?? '');
            if ($logoPath !== $path) {
                $currentLogos[] = is_string($logo) ? ['path' => $logo, 'order' => 999] : $logo;
            }
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        }

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'site_logos'],
            ['value' => json_encode(array_values($currentLogos)), 'type' => 'text']
        );

        return redirect()->route('admin.settings.index')->with('success', 'Logo eliminado correctamente.');
    }

    public function updateLogoOrder(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'order' => 'required|integer'
        ]);

        $path = $request->input('path');
        $order = $request->input('order');

        $rawLogos = json_decode(\App\Models\SiteSetting::getSetting('site_logos', '[]'), true) ?: [];
        $currentLogos = [];
        foreach ($rawLogos as $logo) {
            if (is_string($logo)) {
                $currentLogos[] = ['path' => $logo, 'order' => ($logo === $path ? $order : 999)];
            } else if (is_array($logo)) {
                if (isset($logo['path']) && $logo['path'] === $path) {
                    $logo['order'] = $order;
                }
                $currentLogos[] = $logo;
            }
        }

        \App\Models\SiteSetting::updateOrCreate(
            ['key' => 'site_logos'],
            ['value' => json_encode(array_values($currentLogos)), 'type' => 'text']
        );

        return redirect()->route('admin.settings.index')->with('success', 'Orden actualizado.');
    }

    public function downloadParentalConsent()
    {
        $template = \App\Models\SiteSetting::getSetting('parental_consent_template', '');
        $pdfPath = \App\Models\SiteSetting::getSetting('parental_consent_pdf', '');

        if (!empty($template)) {
            $userName = 'Modelo';
            
            $template = str_replace(
                [
                    '<nombre>', '&lt;nombre&gt;', '[nombre]', '[NOMBRE]',
                    '<evento>', '&lt;evento&gt;', '[evento]', '[EVENTO]',
                    '<fecha>', '&lt;fecha&gt;', '[fecha]', '[FECHA]'
                ],
                [
                    '________________________________________', '________________________________________', '________________________________________', '________________________________________',
                    '________________________________________________________________', '________________________________________________________________', '________________________________________________________________', '________________________________________________________________',
                    'Fecha: ____________________', 'Fecha: ____________________', 'Fecha: ____________________', 'Fecha: ____________________'
                ],
                $template
            );

            return view('shared.parental_consent_pdf', compact('template', 'userName'));
        } elseif (!empty($pdfPath) && \Illuminate\Support\Facades\Storage::disk('public')->exists($pdfPath)) {
            return response()->download(\Illuminate\Support\Facades\Storage::disk('public')->path($pdfPath), 'modelo_justificante_parental.pdf');
        }

        return redirect()->back()->with('error', 'No hay modelo de justificante configurado.');
    }

    public function generateBackup()
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST', '127.0.0.1');

        $backupFileName = 'backup_banda_' . date('Y_m_d_His') . '.zip';
        $backupPath = storage_path('app/' . $backupFileName);

        $zip = new \ZipArchive();
        if ($zip->open($backupPath, \ZipArchive::CREATE) !== true) {
            return back()->with('error', 'No se pudo crear el archivo ZIP.');
        }

        $rawBackupPass = \App\Models\SiteSetting::getSetting('backup_password', '');
        $backupPassword = '@Acemila2026';
        if ($rawBackupPass) {
            try {
                $backupPassword = \Illuminate\Support\Facades\Crypt::decryptString($rawBackupPass);
            } catch (\Exception $e) {}
        }
        
        if ($backupPassword) {
            $zip->setPassword($backupPassword);
        }

        // 1. Backup de base de datos
        $sqlFile = storage_path('app/temp_db_backup.sql');
        $mysqldumpPath = 'C:\\xampp_2023\\mysql\\bin\\mysqldump.exe';
        if (!file_exists($mysqldumpPath)) {
            $mysqldumpPath = 'mysqldump'; // Fallback a global
        }

        $passwordArg = $dbPass ? "-p\"{$dbPass}\"" : "";
        $command = "\"{$mysqldumpPath}\" -u \"{$dbUser}\" {$passwordArg} -h {$dbHost} {$dbName} > \"{$sqlFile}\"";
        
        exec($command, $output, $returnVar);

        if ($returnVar === 0 && file_exists($sqlFile)) {
            $zip->addFile($sqlFile, 'database_backup.sql');
            if ($backupPassword) $zip->setEncryptionName('database_backup.sql', \ZipArchive::EM_TRAD_PKWARE);
        } else {
            $zip->addFromString('db_backup_error.txt', "No se pudo generar la copia de la base de datos. Asegǧrate de que mysqldump estǸ disponible.\nComando intentado: " . $command);
            if ($backupPassword) $zip->setEncryptionName('db_backup_error.txt', \ZipArchive::EM_TRAD_PKWARE);
        }

        // 2. Backup de archivos relevantes (partituras, logos, etc en public storage)
        $publicStorage = storage_path('app/public');
        if (is_dir($publicStorage)) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($publicStorage),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = 'archivos_publicos/' . substr($filePath, strlen($publicStorage) + 1);
                    $zip->addFile($filePath, $relativePath);
                    if ($backupPassword) $zip->setEncryptionName($relativePath, \ZipArchive::EM_TRAD_PKWARE);
                }
            }
        }
        
        // 3. Incluir archivo .env
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $zip->addFile($envPath, 'config_env.txt');
            if ($backupPassword) $zip->setEncryptionName('config_env.txt', \ZipArchive::EM_TRAD_PKWARE);
        }

        $zip->close();

        if (file_exists($sqlFile)) {
            @unlink($sqlFile);
        }

        $response = response()->download($backupPath)->deleteFileAfterSend(true);
        $response->headers->setCookie(cookie('backup_downloaded', '1', 1, null, null, false, false));
        return $response;
    }
}
