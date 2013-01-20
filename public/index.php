<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();

$input = $request->get( 'name', 'World' );

$response = new Response( 'Hello ' . htmlspecialchars( $input, ENT_QUOTES, 'UTF-8' ) );

// Ensure that our Response were compliant with the HTTP specification
$response->prepare( $request );

$response->setStatusCode(200);
$response->headers->set('Content-Type', 'text/html');
$response->setMaxAge( 10 );

$response->send();