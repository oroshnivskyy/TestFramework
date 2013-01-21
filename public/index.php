<?php
require_once __DIR__ . '/../vendor/autoload.php';
define( 'BASE_PATH', __DIR__ . '/..' );

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Controller\LeapYearController;

new LeapYearController();
$request = Request::createFromGlobals();
$routes = include __DIR__ . '/../app/app.php';

$context = new Routing\RequestContext();
$context->fromRequest( $request );
$matcher = new Routing\Matcher\UrlMatcher( $routes, $context );
$resolver = new HttpKernel\Controller\ControllerResolver();

try{
    $request->attributes->add( $matcher->match( $request->getPathInfo() ) );
    $controller = $resolver->getController( $request );
    $arguments = $resolver->getArguments( $request, $controller );

    $response = call_user_func_array( $controller, $arguments );
} catch ( Routing\Exception\ResourceNotFoundException $e ){
    $response = new Response( 'Not Found', 404 );
} catch ( Exception $e ){
    echo $e->getMessage();
    $response = new Response( 'An error occurred', 500 );
}

$response->send();

function render_template( Request $request ){
    // Extracts _route and other routing parameters
    extract( $request->attributes->all(), EXTR_SKIP );
    ob_start();
    include sprintf( BASE_PATH . '/src/pages/%s.php', $_route );

    return new Response( ob_get_clean() );
}