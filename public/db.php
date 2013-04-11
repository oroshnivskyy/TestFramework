<?php
require_once __DIR__ . '/../vendor/autoload.php';
define( 'BASE_PATH', __DIR__ . '/..' );

require_once BASE_PATH . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

include BASE_PATH . '/app/phalcon_container.php';
$sc = getContainer(true);

$request = Request::createFromGlobals();

$response = $sc->getFramework()->handle($request);

$response->send();

function render_template( \Symfony\Component\HttpFoundation\Request $request ){
    // Extracts _route and other routing parameters
    extract( $request->attributes->all(), EXTR_SKIP );
    ob_start();
    include sprintf( BASE_PATH . '/src/pages/%s.php', $_route );

    return new Response( ob_get_clean() );
}