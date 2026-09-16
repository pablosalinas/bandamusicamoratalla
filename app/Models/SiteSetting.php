<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;
    
    protected $fillable = ['key', 'value', 'type'];
    
    public static function getSetting($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function isDashboardCardEnabled(string $key): bool
    {
        $val = self::getSetting($key, '1');
        return $val === '1' || $val === true || $val === 1 || $val === 'true';
    }

    public static function getDashboardCards(): array
    {
        return [
            'dashboard_card_users' => [
                'title' => 'Usuarios / Músicos',
                'description' => 'Muestra el número total de usuarios y enlace a la gestión de músicos.',
                'icon' => 'users',
                'color' => 'amber',
            ],
            'dashboard_card_sheet_music' => [
                'title' => 'Partituras',
                'description' => 'Muestra el total de obras y acceso al archivo de partituras.',
                'icon' => 'sheet_music',
                'color' => 'blue',
            ],
            'dashboard_card_instruments' => [
                'title' => 'Catálogo de Instrumentos',
                'description' => 'Muestra los tipos de instrumentos catalogados en la banda.',
                'icon' => 'instruments',
                'color' => 'indigo',
            ],
            'dashboard_card_inventory' => [
                'title' => 'Artículos de Inventario',
                'description' => 'Muestra el número de instrumentos físicos en inventario.',
                'icon' => 'inventory',
                'color' => 'cyan',
            ],
            'dashboard_card_boards' => [
                'title' => 'Junta Directiva',
                'description' => 'Muestra los componentes de la directiva y actas de reuniones.',
                'icon' => 'boards',
                'color' => 'emerald',
            ],
            'dashboard_card_events' => [
                'title' => 'Eventos y Planning',
                'description' => 'Muestra el número de eventos y control de asistencias.',
                'icon' => 'events',
                'color' => 'rose',
            ],
            'dashboard_card_news' => [
                'title' => 'Noticias',
                'description' => 'Muestra las noticias publicadas en la web.',
                'icon' => 'news',
                'color' => 'purple',
            ],
            'dashboard_card_media' => [
                'title' => 'Archivos Multimedia / Sonoro',
                'description' => 'Muestra los audios y vídeos del archivo sonoro.',
                'icon' => 'media',
                'color' => 'fuchsia',
            ],
            'dashboard_card_instrument_brands' => [
                'title' => 'Marcas de Instrumentos',
                'description' => 'Muestra las marcas comerciales registradas.',
                'icon' => 'brands',
                'color' => 'teal',
            ],
            'dashboard_card_fiscal_years' => [
                'title' => 'Contabilidad / Ejercicios',
                'description' => 'Muestra los años contables y balances económicos.',
                'icon' => 'accounting',
                'color' => 'green',
            ],
            'dashboard_card_settings' => [
                'title' => 'Ajustes Web',
                'description' => 'Acceso directo a la configuración general de la plataforma.',
                'icon' => 'settings',
                'color' => 'gray',
            ],
            'dashboard_card_analytics' => [
                'title' => 'Estadísticas de Visitas',
                'description' => 'Muestra las visitas registradas y analítica web.',
                'icon' => 'analytics',
                'color' => 'orange',
            ],
            'dashboard_card_logs' => [
                'title' => 'Registros de Actividad',
                'description' => 'Muestra el log de auditoría y acciones realizadas.',
                'icon' => 'logs',
                'color' => 'pink',
            ],
            'dashboard_card_manual' => [
                'title' => 'Manual y Ayuda',
                'description' => 'Acceso directo al manual de administración de la banda.',
                'icon' => 'manual',
                'color' => 'yellow',
            ],
        ];
    }
}
