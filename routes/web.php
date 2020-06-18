<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use RabbitCMS\Modules\Module;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

/* @var Router $router */

$router->post('callback/{shop}', 'CallbackAction@handle')->name('callback')
    ->withoutMiddleware([VerifyCsrfToken::class, \App\Http\Middleware\VerifyCsrfToken::class]);
