<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URIs to exclude from CSRF protection
     */
    protected $except = [
        // leave empty to let Sanctum work properly
    ];
}
