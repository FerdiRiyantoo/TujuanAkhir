<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/login', 'AuthController::index');
$routes->post('/login-process', 'AuthController::loginProcess');
$routes->get('/logout', 'AuthController::logout');


$routes->group('admin', ['filter' => 'admin_filter'], function ($routes) {

    $routes->get('dashboard', 'Admin\DashboardController::index');

    $routes->get('menu', 'Admin\MenuController::index');
    $routes->get('menu/new', 'Admin\MenuController::new');
    $routes->post('menu/create', 'Admin\MenuController::create');
    $routes->get('menu/edit/(:num)', 'Admin\MenuController::edit/$1');
    $routes->post('menu/update/(:num)', 'Admin\MenuController::update/$1');
    $routes->get('menu/delete/(:num)', 'Admin\MenuController::delete/$1');

    $routes->get('laporan', 'Admin\LaporanController::index');
    $routes->get('laporan/excel', 'Admin\LaporanController::exportExcel');

    $routes->get('users', 'Admin\UserController::index');
    $routes->get('users/new', 'Admin\UserController::new');
    $routes->post('users/create', 'Admin\UserController::create');
    $routes->get('users/edit/(:num)', 'Admin\UserController::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\UserController::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\UserController::delete/$1');
});