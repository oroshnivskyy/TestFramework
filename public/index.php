<?php

$input = isset( $_GET[ 'name' ] ) ? $_GET[ 'name' ] : 'World';

header( 'Content-Type: text/html; charset=utf-8' );

echo 'Hello ', htmlspecialchars( $input, ENT_QUOTES, 'UTF-8' );