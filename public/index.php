<?php
require_once __DIR__ . '/../vendor/autoload.php';
define( 'BASE_PATH', __DIR__ . '/..' );

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

$routes = array(
    '/hello' => '/pages/hello.php',
    '/bye' => '/pages/bye.php',
);

$path = $request->getPathInfo();

if ( isset( $routes[ $path ] ) ){
    ob_start();
    require BASE_PATH . '/src' . $routes[ $path ];
    $response->setContent( ob_get_clean() );
} else{
    $response->setStatusCode( 404 );
    $response->setContent( 'Not Found' );
}

$response->send();
