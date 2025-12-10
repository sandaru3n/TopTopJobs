<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Home::dashboard', ['filter' => 'auth']);
$routes->get('/dashboard/', 'Home::dashboard', ['filter' => 'auth']);
$routes->get('/jobs', 'Home::jobs');
$routes->get('/job/(:segment)', 'Home::jobDetails');
$routes->get('/job/(:segment)/', 'Home::jobDetails');
// Fallback route for /public/job/... URLs (will be redirected by .htaccess, but this handles if redirect doesn't work)
$routes->get('/public/job/(:segment)', 'Home::jobDetails');
$routes->get('/public/job/(:segment)/', 'Home::jobDetails');
$routes->get('/job-details', 'Home::jobDetails');
$routes->get('/post-job', 'Home::postJob');
$routes->post('/post-job', 'Home::processPostJob');

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

// Profile routes (protected)
$routes->get('profile', 'AuthController::profile', ['filter' => 'auth']);
$routes->post('profile/update', 'AuthController::updateProfile', ['filter' => 'auth']);
$routes->post('profile/password', 'AuthController::updatePassword', ['filter' => 'auth']);

// Manage Jobs routes (protected)
$routes->get('manage-jobs', 'Home::manageJobs', ['filter' => 'auth']);
$routes->get('edit-job/(:num)', 'Home::editJob', ['filter' => 'auth']);
$routes->post('update-job/(:num)', 'Home::updateJob', ['filter' => 'auth']);

// Saved Jobs routes
$routes->get('saved-jobs', 'Home::savedJobs', ['filter' => 'auth']);
$routes->post('api/toggle-save-job', 'Home::toggleSaveJob', ['filter' => 'auth']);
$routes->get('api/check-saved-job/(:num)', 'Home::checkSavedJob');
$routes->get('api/check-saved-job', 'Home::checkSavedJob');

// Protected Admin Routes
$routes->group('admin', ['filter' => 'auth:admin'], function($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
});
