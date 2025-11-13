<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Tambahkan route yang mau dilewati CSRF
         '/customer/register',   // kalau mau lewati register
        // 'api/*',       // kalau semua API dilewati
    ];
}
