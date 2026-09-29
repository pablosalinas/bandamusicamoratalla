<?php

namespace App\Services;

use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    /**
     * Correo destinatario para pruebas de alertas de validaciones pendientes (directiva/administración).
     */
    public const ALERT_RECIPIENT = 'pablosalinasmarin@gmail.com';

    /**
     * Configura y purga el transporte SMTP en tiempo de ejecución a partir de los ajustes guardados.
     * Retorna array con los datos o null si no está configurado.
     */
    protected static function setupSmtp(): ?array
    {
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

        if (empty($mailHost) || empty($mailUser) || empty($mailPass)) {
            Log::info('EmailNotificationService: SMTP no configurado o credenciales incompletas.');
            return null;
        }

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

        return [
            'bandName' => $bandName,
            'mailUser' => $mailUser,
            'mailFromAddress' => $mailFromAddress ?: $mailUser,
            'mailFromName' => $mailFromName ?: $bandName,
        ];
    }

    /**
     * Envía un correo de notificación de validación pendiente a la cuenta configurada para la administración.
     *
     * @param string $tipo 'musician' o 'instrument'
     * @param string $titulo Título o nombre del recurso
     * @param array $detalles Clave => Valor con información relevante
     * @return bool
     */
    public static function sendPendingValidationAlert(string $tipo, string $titulo, array $detalles = []): bool
    {
        try {
            $smtp = self::setupSmtp();
            if (!$smtp) {
                return false;
            }

            $bandName = $smtp['bandName'];
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

            Mail::mailer('smtp')->raw($cuerpo, function ($message) use ($recipient, $subject, $smtp) {
                if ($smtp['mailFromAddress']) {
                    $message->from($smtp['mailFromAddress'], $smtp['mailFromName']);
                }
                $message->to($recipient)->subject($subject);
            });

            Log::info("EmailNotificationService: Alerta enviada con éxito a {$recipient} para {$tipo}: {$titulo}");
            return true;
        } catch (\Exception $e) {
            Log::error("EmailNotificationService: Error al enviar correo de alerta: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envía un correo al músico notificando que su ficha de usuario ha sido aprobada y validada.
     */
    public static function sendMusicianValidatedNotice(User $user): bool
    {
        if (empty($user->email)) {
            return false;
        }

        try {
            $smtp = self::setupSmtp();
            if (!$smtp) {
                return false;
            }

            $bandName = $smtp['bandName'];
            $subject = "✅ ¡Tu cuenta ha sido validada y activada! - {$bandName}";

            $cuerpo = "¡Hola, {$user->name}!\n\n";
            $cuerpo .= "Nos alegra comunicarte que la directiva de la {$bandName} ha revisado y validado tu solicitud de alta.\n\n";
            $cuerpo .= "Tu cuenta ya está completamente activa. Ya puedes iniciar sesión con tu correo electrónico y tu contraseña en la aplicación oficial:\n";
            $cuerpo .= url('/login') . "\n\n";
            $cuerpo .= "Desde tu panel podrás consultar el planning de ensayos y actuaciones, acceder a tus partituras y gestionar tus datos e instrumentos.\n\n";
            $cuerpo .= "--------------------------------------------------\n";
            $cuerpo .= "⚠️ AVISO IMPORTANTE: Este es un mensaje generado automáticamente por el sistema desde la dirección {$smtp['mailFromAddress']}. Por favor, no respondas a este correo ya que este buzón no admite respuestas ni es atendido por personas.\n";
            $cuerpo .= "--------------------------------------------------\n\n";
            $cuerpo .= "¡Bienvenido/a y buena música!\n";
            $cuerpo .= "Junta Directiva - {$bandName}";

            Mail::mailer('smtp')->raw($cuerpo, function ($message) use ($user, $subject, $smtp) {
                if ($smtp['mailFromAddress']) {
                    $message->from($smtp['mailFromAddress'], $smtp['mailFromName']);
                }
                $message->to($user->email, "{$user->name} {$user->last_name}")
                        ->subject($subject);
            });

            Log::info("EmailNotificationService: Aviso de validación de usuario enviado a {$user->email}");
            return true;
        } catch (\Exception $e) {
            Log::error("EmailNotificationService: Error al notificar validación al usuario {$user->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Envía un correo al músico cuando uno de sus instrumentos asociados ha sido validado por la directiva.
     */
    public static function sendInstrumentValidatedNotice(Inventory $inventory): bool
    {
        try {
            // Asegurar que las relaciones con usuarios y catálogo de instrumentos estén cargadas
            $inventory->loadMissing(['users', 'instrument']);

            $musicians = $inventory->users;
            if ($musicians->isEmpty()) {
                return false;
            }

            $smtp = self::setupSmtp();
            if (!$smtp) {
                return false;
            }

            $bandName = $smtp['bandName'];
            $instrumentName = $inventory->instrument ? $inventory->instrument->name : 'Instrumento';
            $instrumentTitle = $instrumentName . ($inventory->model ? " ({$inventory->model})" : '');

            $subject = "🎷 ¡Tu instrumento ha sido validado! - {$bandName}";

            foreach ($musicians as $user) {
                if (empty($user->email)) {
                    continue;
                }

                $cuerpo = "¡Hola, {$user->name}!\n\n";
                $cuerpo .= "Te comunicamos que el instrumento que diste de alta ha sido revisado, validado y aprobado oficialmente en el inventario de la {$bandName}.\n\n";
                $cuerpo .= "Detalles del instrumento validado:\n";
                $cuerpo .= "--------------------------------------------------\n";
                $cuerpo .= "• Instrumento: {$instrumentName}\n";
                if ($inventory->model) {
                    $cuerpo .= "• Modelo: {$inventory->model}\n";
                }
                if ($inventory->serial_number) {
                    $cuerpo .= "• Número de serie: {$inventory->serial_number}\n";
                }
                $cuerpo .= "• Propiedad: " . strtoupper($inventory->propiedad) . "\n";
                if ($inventory->tipo_partitura) {
                    $cuerpo .= "• Voz / Tipo de partitura asignada: {$inventory->tipo_partitura}\n";
                }
                $cuerpo .= "--------------------------------------------------\n\n";
                $cuerpo .= "Ya tienes acceso completo al repertorio y partituras correspondientes desde tu portal de músico:\n";
                $cuerpo .= url('/dashboard') . "\n\n";
                $cuerpo .= "--------------------------------------------------\n";
                $cuerpo .= "⚠️ AVISO IMPORTANTE: Este es un mensaje generado automáticamente por el sistema desde la dirección {$smtp['mailFromAddress']}. Por favor, no respondas a este correo ya que este buzón no admite respuestas ni es atendido por personas.\n";
                $cuerpo .= "--------------------------------------------------\n\n";
                $cuerpo .= "Un cordial saludo,\n";
                $cuerpo .= "Junta Directiva - {$bandName}";

                Mail::mailer('smtp')->raw($cuerpo, function ($message) use ($user, $subject, $smtp) {
                    if ($smtp['mailFromAddress']) {
                        $message->from($smtp['mailFromAddress'], $smtp['mailFromName']);
                    }
                    $message->to($user->email, "{$user->name} {$user->last_name}")
                            ->subject($subject);
                });

                Log::info("EmailNotificationService: Aviso de validación de instrumento {$inventory->id} enviado a {$user->email}");
            }

            return true;
        } catch (\Exception $e) {
            Log::error("EmailNotificationService: Error al enviar aviso de instrumento validado: " . $e->getMessage());
            return false;
        }
    }
}
