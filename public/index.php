<?php
require_once __DIR__ . '/../vendor/autoload.php';
define( 'BASE_PATH', __DIR__ . '/..' );

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;

$request = Request::createFromGlobals();
$routes = include BASE_PATH . '/app/app.php';

$context = new Routing\RequestContext();
$context->fromRequest( $request );
$matcher = new Routing\Matcher\UrlMatcher( $routes, $context );
$resolver = new HttpKernel\Controller\ControllerResolver();

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber( new Simple\ResponseSubscriber() );

$framework = new Simple\Framework( $dispatcher, $matcher, $resolver );
$framework = new HttpKernel\HttpCache\HttpCache( $framework, new HttpKernel\HttpCache\Store( BASE_PATH . '/cache' ) );
$response = $framework->handle( $request );

$response->send();

function render_template( \Symfony\Component\HttpFoundation\Request $request ){
    // Extracts _route and other routing parameters
    extract( $request->attributes->all(), EXTR_SKIP );
    ob_start();
    include sprintf( BASE_PATH . '/src/pages/%s.php', $_route );

    return new Response( ob_get_clean() );
}