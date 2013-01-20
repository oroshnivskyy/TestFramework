<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

$routes = array(
    '/hello' => __DIR__ . '/hello.php',
    '/bye' => __DIR__ . '/bye.php',
);

$path = $request->getPathInfo();

if ( isset( $routes[ $path ] ) ){
    require $routes[ $path ];
} else{
    $response->setStatusCode( 404 );
    $response->setContent( 'Not Found' );
}

$response->send();
