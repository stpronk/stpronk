<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withProviders([
//        \Sytatsu\Essentials\EssentialsServiceProvider::class,
//        \Stpronk\Assets\AssetsServiceProvider::class,
//        \Stpronk\Todos\TodosServiceProvider::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        // Caddy terminates TLS and forwards plain HTTP to nginx/php-fpm over
        // the internal `proxy` docker network (see docker-compose.prod.yml) --
        // without this, Laravel never sees the request as HTTPS and generates
        // http:// asset/stylesheet URLs on an https:// page, which browsers
        // block as mixed content. Trusting '*' is safe here because nginx/
        // php-fpm publish no host ports; the proxy network is the only way in.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
