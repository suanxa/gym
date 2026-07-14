<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

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
    public function boot()
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifikasi Email Piai Wellness')
                ->line('Klik tombol di bawah ini untuk mengaktifkan akun Anda.')
                ->action('Verifikasi Akun', $url) // URL ini tetap ke Laravel
                ->line('Jika Anda tidak merasa mendaftar, abaikan email ini.');
        });

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');


        View::composer('layouts.sidebar', function ($view) {
            $user = Auth::user();
            
            // Logika dashboard route berdasarkan role
            $dashboardRoute = ($user && $user->role === 'member') ? 'member.dashboard' : 'admin.dashboard';
            
            // Ambil data setting pertama (menggunakan cache agar performa tetap kencang)
            $siteSetting = Setting::first();

            $view->with([
                'dashboardRoute' => $dashboardRoute,
                'siteSetting'    => $siteSetting
            ]);
        });

    }
}
