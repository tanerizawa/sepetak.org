<?php

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        // `articles:generate` memeriksa `ArticlePool::isDueAt()` per pool (ringan bila tidak ada yang jatuh tempo).
        $schedule->command('articles:generate')->everyMinute()->withoutOverlapping();
    })
    ->withMiddleware(function (Middleware $middleware) {
        // Di belakang reverse proxy (Nginx, Cloudflare, load balancer), tanpa ini
        // `$request->secure()` salah → cookie `secure`/sesi tidak selaras → 419 saat login.
        $middleware->trustProxies(at: '*');
        $middleware->append(SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
