<?php
require_once __DIR__ . '/../vendor/autoload.php';
define( 'BASE_PATH', __DIR__ . '/..' );

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

$request = Request::createFromGlobals();
$routes = include __DIR__ . '/../app/app.php';

$context = new Routing\RequestContext();
$context->fromRequest( $request );
$matcher = new Routing\Matcher\UrlMatcher( $routes, $context );

try{
    // Extracts _route and other routing parameters
    extract( $matcher->match( $request->getPathInfo() ), EXTR_SKIP );

    ob_start();
    include sprintf( BASE_PATH . '/src/pages/%s.php', $_route );

    $response = new Response( ob_get_clean() );
} catch ( Routing\Exception\ResourceNotFoundException $e ){
    $response = new Response( 'Not Found', 404 );
} catch ( Exception $e ){
    $response = new Response( 'An error occurred', 500 );
}

$response->send();
