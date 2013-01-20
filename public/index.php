<?php
require_once __DIR__ . '/../vendor/autoload.php';
define( 'BASE_PATH', __DIR__ . '/..' );

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

$routes = array(
    '/hello' => 'hello',
    '/bye' => 'bye',
);

$path = $request->getPathInfo();

if ( isset( $routes[ $path ] ) ){
    ob_start();
    extract( $request->query->all(), EXTR_SKIP );
    include sprintf( BASE_PATH . '/src/pages/%s.php', $routes[ $path ] );
    $response = new Response( ob_get_clean() );
} else{
    $response = new Response( 'Not Found', 404 );
}

$response->send();
