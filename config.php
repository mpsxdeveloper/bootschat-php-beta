<?php

require 'environment.php';

global $config;
$config = array();

if(ENVIRONMENT == 'development') {
    define("BASE", "http://localhost/bootschat-php/");
    $config['dbname'] = 'bootschat';
    $config['dbhost'] = 'localhost';
    $config['dbuser'] = 'root';
    $config['dbpass'] = '';
}
else {
    define("BASE", "");
    $config['dbname'] = '';
    $config['dbhost'] = '';
    $config['dbuser'] = '';
    $config['dbpass'] = '';
}