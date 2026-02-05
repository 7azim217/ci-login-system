<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'HomeController::index');
$routes->get('login', 'HomeController::loginPage');
$routes->get('register', 'HomeController::registerPage');
$routes->get('dashboard', 'HomeController::dashboard');

$routes->group('api', function($routes) {
    $routes->post('register', 'AuthController::register');
    $routes->post('login', 'AuthController::login');
    $routes->get('profile', 'AuthController::profile', ['filter' => 'jwt']);
    $routes->post('logout', 'AuthController::logout', ['filter' => 'jwt']);
});