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
$matcher = new Routing\Matcher\UrlMatcher( $routes, $context );
$resolver = new HttpKernel\Controller\ControllerResolver();

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber( new Simple\ResponseSubscriber() );
$dispatcher->addSubscriber ( new HttpKernel\EventListener\RouterListener( $matcher ) );
$listener = new HttpKernel\EventListener\ExceptionListener( "Simple\ErrorController::exceptionAction" );
$dispatcher->addSubscriber( $listener );
$dispatcher->addSubscriber( new HttpKernel\EventListener\ResponseListener( 'UTF-8' ) );
$dispatcher->addSubscriber( new Simple\StringResponseListener() );

// For StreamedResponses
//$dispatcher->addSubscriber(new HttpKernel\EventListener\StreamedResponseListener());

$framework = new Simple\Framework( $dispatcher, $resolver );
//$framework = new HttpKernel\HttpCache\HttpCache( $framework, new HttpKernel\HttpCache\Store( BASE_PATH . '/cache' ) );
$response = $framework->handle( $request );

$response->send();

function render_template( \Symfony\Component\HttpFoundation\Request $request ){
    // Extracts _route and other routing parameters
    extract( $request->attributes->all(), EXTR_SKIP );
    ob_start();
    include sprintf( BASE_PATH . '/src/pages/%s.php', $_route );

    return new Response( ob_get_clean() );
}