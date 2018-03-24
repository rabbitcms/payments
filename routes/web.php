<?php
declare(strict_types=1);

use Illuminate\Routing\Router;
use RabbitCMS\Modules\Module;

/* @var Router $router */

Route::post('callback/{shop}', 'CallbackAction@handle')->name('callback');
