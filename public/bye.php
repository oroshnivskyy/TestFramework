<?php

require_once __DIR__.'/../app/init.php';

$response->setContent('Goodbye!');
$response->send();