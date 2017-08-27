<?php
// Application middleware
// e.g: $app->add(new \Slim\Csrf\Guard);

$container = $app->getContainer();

$app->add(new \Slim\Middleware\JwtAuthentication([
    "attribute" => "jwt",
    "path" => '/api',
    "passthrough" => ["/api/client/auth", "/api/company/auth"],
    "secret" => $container['settings']['jwt']['secret'],
    "algorithm" => ["HS256"],
    "callback" => function ($request, $response, $arguments) {
        /**@var $request \Slim\Http\Request*/
        $scopes = $arguments["decoded"]->scope;
        $path = $request->getUri()->getPath();
        $require = strpos($path, '/api/client') !== FALSE ? 'client' : 'company';

        return in_array($require, $scopes);
    }
]));


$app->add(new \Tuupola\Middleware\Cors([
    "origin" => ["*"],
    "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
    "headers.allow" => ["Authorization"],
    "headers.expose" => [],
    "credentials" => true,
    "cache" => 0,
]));