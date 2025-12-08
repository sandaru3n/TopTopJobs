<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/jobs', 'Home::jobs');

// Authentication Routes
$routes->group('auth', ['filter' => 'guest'], function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->get('signup', 'AuthController::signup');
    $routes->post('processLogin', 'AuthController::processLogin');
    $routes->post('processSignup', 'AuthController::processSignup');
});

// Alternative routes (without auth prefix)
$routes->get('login', 'AuthController::login', ['filter' => 'guest']);
$routes->get('signup', 'AuthController::signup', ['filter' => 'guest']);
$routes->get('logout', 'AuthController::logout', ['filter' => 'auth']);

// Protected Admin Routes
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
});

// Protected Employer Routes
$routes->group('employer', ['filter' => 'auth:employer'], function($routes) {
    $routes->get('dashboard', 'EmployerController::dashboard');
});
