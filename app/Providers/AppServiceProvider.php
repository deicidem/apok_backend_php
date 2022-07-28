<?php

namespace App\Providers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        VerifyEmail::toMailUsing(function ($notifiable) {
            $verifyUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(60),
                ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
            );
            $verifyUrl = str_replace('192.168.1.104/apok_backend_php/public', 'localhost:8080', $verifyUrl);
            return (new MailMessage)
                ->subject('Подтверждение электронной почты!')
                ->markdown('emails.emailVerification', ['url' => $verifyUrl]);
        });

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage)
                ->subject('Восстановление пароля')
                ->line('Вы получили это письмо, потому мы что мы получили запрос на восстановление пароля вашего аккаунта')
                ->action('Восстановить пароль', 'http://localhost:8080/reset-password?token=' . $token . '&email=' . $notifiable->email)
                ->line('Ссылка для восстановления пароля действительная в течение in :count минут.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')])
                ->line('Если вы получили это письмо по ошибке, просто проигнорируйте его.');
        });
    }
}
