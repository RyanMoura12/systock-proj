<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * As rotas que não precisam de CSRF.
     *
     * @var array<int, string>
     */
    protected $except = [
        'users',        // Desabilita CSRF para a rota /users
        'api/*',        // Desabilita CSRF para todas as rotas API
    ];
}
