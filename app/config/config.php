<?php

$root = "http://" . $_SERVER['HTTP_HOST'];
$root .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
define('BASEURL', $root);

// DB
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'phpmvc');
