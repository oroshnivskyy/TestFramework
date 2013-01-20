<?php

require_once __DIR__.'/../app/init.php';

$input = $request->get('name', 'World');

$response->setContent( 'Hello ' . htmlspecialchars( $input, ENT_QUOTES, 'UTF-8' ) );
$response->send();