<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\ResetPassword; // <--- Añade esta línea
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Definir longitud por defecto para migraciones si aplica
        Schema::defaultStringLength(191);

        // Forzar HTTPS en entorno de producción
        if (config('app.env') === 'production' || app()->environment('production')) {
            URL::forceScheme('https');
        }

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $expireMinutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

            return (new MailMessage)
                ->subject('Restablecer contraseña - ' . config('app.name'))
                ->greeting('¡Hola!')
                ->line('Estás recibiendo este correo porque hemos recibido una solicitud de restablecimiento de contraseña para tu cuenta.')
                ->action('Restablecer contraseña', $url)
                ->line("Este enlace de restablecimiento caducará en {$expireMinutes} minutos.")
                ->line('Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna otra acción.')
                ->salutation("Saludos,\n" . config('app.name'));
        });
    }
}