<?php

$input = isset($_GET['name']) ? $_GET['name'] : 'World';

echo "Hello {$input}";