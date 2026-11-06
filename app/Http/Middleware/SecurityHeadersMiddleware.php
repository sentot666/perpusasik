<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and attach OWASP recommended security headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Clickjacking protection (OWASP A05)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME-type sniffing (OWASP A05)
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Cross-Site Scripting protection filter
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Restrict sensitive device APIs
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}

