<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

/**
 * Login page kustom SEPETAK — meneruskan seluruh logic auth dari
 * Filament\Pages\Auth\Login dan hanya mengganti view agar tampilan
 * mengikuti bahasa visual "Tani Merah" (poster split-layout).
 */
class Login extends BaseLogin
{
    /**
     * @var view-string
     */
    protected static string $view = 'filament.pages.auth.login';

    /**
     * @var view-string
     */
    protected static string $layout = 'filament.components.layout.split';
}
