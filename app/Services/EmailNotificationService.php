<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    /**
     * Correo destinatario para pruebas de alertas de validaciones pendientes.
     */
    public const ALERT_RECIPIENT = 'pablosalinasmarin@gmail.com';

    /**
     * Envía un correo de notificación de validación pendiente a la cuenta configurada.
     *
     * @param string $tipo 'musician' o 'instrument'
     * @param string $titulo Título o nombre del recurso
     * @param array $detalles Clave => Valor con información relevante
     * @return bool
     */
    public static function sendPendingValidationAlert(string $tipo, string $titulo, array $detalles = []): bool
    {
        try {
            $bandName = SiteSetting::getSetting('band_name', 'Banda de Música de Moratalla');
            $mailHost = SiteSetting::getSetting('mail_host', 'smtp.ionos.es');
            $mailPort = (int) SiteSetting::getSetting('mail_port', 587);
            $mailEnc = SiteSetting::getSetting('mail_encryption', 'tls');
            $mailUser = SiteSetting::getSetting('mail_username', '');
            $mailFromAddress = SiteSetting::getSetting('mail_from_address', $mailUser);
            $mailFromName = SiteSetting::getSetting('mail_from_name', $bandName);

            $rawMailPass = SiteSetting::getSetting('mail_password', '');
            $mailPass = '';
            if ($rawMailPass) {
                try {
                    $mailPass = Crypt::decryptString($rawMailPass);
                } catch (\Exception $e) {
                    $mailPass = '';
                }
            }

            // Si los parámetros SMTP no están configurados, no intentar enviar
            if (empty($mailHost) || empty($mailUser) || empty($mailPass)) {
                Log::info('EmailNotificationService: SMTP no configurado o credenciales incompletas.');
                return false;
            }

            // Configurar dinámicamente y purgar la instancia SMTP
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $mailHost,
                'mail.mailers.smtp.port' => $mailPort,
                'mail.mailers.smtp.encryption' => ($mailEnc === 'none' || empty($mailEnc)) ? null : $mailEnc,
                'mail.mailers.smtp.username' => $mailUser,
                'mail.mailers.smtp.password' => $mailPass,
                'mail.from.address' => $mailFromAddress ?: $mailUser,
                'mail.from.name' => $mailFromName ?: $bandName,
            ]);

            Mail::purge('smtp');

            $tipoNombre = ($tipo === 'instrument') ? 'Instrumento' : 'Músico / Miembro';
            $subject = "🔔 [VALIDACIÓN PENDIENTE] Nuevo {$tipoNombre}: {$titulo} - {$bandName}";

            $cuerpo = "Hola,\n\n";
            $cuerpo .= "Se ha registrado o marcado como pendiente de validación un {$tipoNombre} en el sistema de la {$bandName}.\n\n";
            $cuerpo .= "Detalles del registro:\n";
            $cuerpo .= "--------------------------------------------------\n";
            $cuerpo .= "• Tipo: {$tipoNombre}\n";
            $cuerpo .= "• Nombre / Referencia: {$titulo}\n";

            foreach ($detalles as $k => $v) {
                if (!empty($v)) {
                    $cuerpo .= "• {$k}: {$v}\n";
                }
            }

            $cuerpo .= "• Fecha y hora: " . now()->format('d/m/Y H:i:s') . "\n";
            $cuerpo .= "--------------------------------------------------\n\n";
            $cuerpo .= "Por favor, accede al panel de administración para revisar y validar este registro cuando sea oportuno.\n\n";
            $cuerpo .= "Saludos,\n{$bandName}";

            $recipient = self::ALERT_RECIPIENT;

            Mail::mailer('smtp')->raw($cuerpo, function ($message) use ($recipient, $subject, $mailFromAddress, $mailUser, $mailFromName, $bandName) {
                $fromEmail = $mailFromAddress ?: $mailUser;
                $fromName = $mailFromName ?: $bandName;
                if ($fromEmail) {
                    $message->from($fromEmail, $fromName);
                }
                $message->to($recipient)
                        ->subject($subject);
            });

            Log::info("EmailNotificationService: Alerta enviada con éxito a {$recipient} para {$tipo}: {$titulo}");
            return true;
        } catch (\Exception $e) {
            Log::error("EmailNotificationService: Error al enviar correo de alerta: " . $e->getMessage());
            return false;
        }
    }
}
