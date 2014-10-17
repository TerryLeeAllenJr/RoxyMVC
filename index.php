<?php
/**
 * Created by PhpStorm.
 * User: LeeAllen
 * Date: 12/4/13
 * Time: 4:19 PM
 */

require 'config.php';
require 'util/Auth.php';
require 'vendor/autoload.php';

use Respect\Rest\Router;
$r3 = new Router;

$r3->get('/', function() {
    $controller = new \Core\Controllers\IndexController;
    $controller->index();
});

$r3->get('/*', function() {
    $controller = new \Core\Controllers\ErrorController;
    $controller->index();
});