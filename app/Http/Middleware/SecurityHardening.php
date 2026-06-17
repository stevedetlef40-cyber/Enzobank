<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHardening
{
    /**
     * Handle an incoming request and apply banking-grade security headers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // --- HSTS (Strict-Transport-Security) ---
        // Force browsers to only communicate over HTTPS for 1 year
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // --- X-Frame-Options ---
        // Prevent Clickjacking attacks
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // --- X-Content-Type-Options ---
        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // --- X-XSS-Protection ---
        // Modern browser XSS filtering
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // --- Referrer-Policy ---
        // Protect privacy by controlling referrer info
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // --- Content-Security-Policy (CSP) ---
        // Note: Minimal CSP to avoid breaking inline theme/chart scripts
        // Allows scripts from same origin and trusted CDN (Google Fonts, FontAwesome, etc.)
        $csp = "default-src 'self'; ";
        $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; ";
        $csp .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; ";
        $csp .= "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; ";
        $csp .= "img-src 'self' data: https:; ";
        $csp .= "connect-src 'self' https:; ";
        $csp .= "frame-ancestors 'none';";
        
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
