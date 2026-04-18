<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menambahkan header keamanan di respons publik:
 *  - Content-Security-Policy
 *  - X-Content-Type-Options
 *  - X-Frame-Options
 *  - Referrer-Policy
 *  - Permissions-Policy
 *
 * Dibatasi hanya untuk respons HTML 2xx agar tidak mengganggu file binary
 * (sitemap XML, export PDF) dan redirect.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $contentType = (string) $response->headers->get('content-type', '');
        if (! str_contains($contentType, 'text/html')) {
            return $response;
        }

        // Admin panel (Filament) butuh inline style/script yang lebih longgar karena
        // Livewire/Alpine. Publik site lebih ketat.
        $isAdmin = $request->is('admin', 'admin/*');

        $scriptSrc = $isAdmin
            ? "'self' 'unsafe-inline' 'unsafe-eval'"
            : "'self' 'unsafe-inline'";

        $styleSrc = "'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net";
        $fontSrc = "'self' https://fonts.gstatic.com https://fonts.bunny.net data:";
        $imgSrc = "'self' data: blob: https: http:";
        $connectSrc = $isAdmin ? "'self' wss: ws:" : "'self'";
        $frameSrc = "'self' https://www.youtube.com https://player.vimeo.com";

        $csp = implode('; ', [
            "default-src 'self'",
            "script-src {$scriptSrc}",
            "style-src {$styleSrc}",
            "font-src {$fontSrc}",
            "img-src {$imgSrc}",
            "media-src 'self' https:",
            "connect-src {$connectSrc}",
            "frame-src {$frameSrc}",
            "frame-ancestors 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);

        $response->headers->set('Content-Security-Policy', $csp, false);
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), interest-cohort=()'
        );

        return $response;
    }
}
