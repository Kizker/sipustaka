<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'CatalogController::index');
$routes->get('books/(:num)', 'CatalogController::show/$1');
$routes->get('offline', 'PwaController::offline');

service('auth')->routes($routes); // login/register Shield

// Area user (wajib login)
$routes->group('', ['filter' => 'session'], static function($routes) {
    $routes->get('my-loans', 'LoanController::myLoans');
    $routes->post('borrow/(:num)', 'LoanController::borrow/$1');
    $routes->post('return/(:num)', 'LoanController::return/$1');
});

// Area admin (wajib group admin)
$routes->group('admin', ['filter' => 'group:admin'], static function($routes) {
    $routes->get('/', 'Admin\DashboardController::index');

    $routes->get('books', 'Admin\BooksController::index');
    $routes->get('books/new', 'Admin\BooksController::new');
    $routes->post('books', 'Admin\BooksController::create');
    $routes->get('books/(:num)/edit', 'Admin\BooksController::edit/$1');
    $routes->post('books/(:num)', 'Admin\BooksController::update/$1');
    $routes->post('books/(:num)/delete', 'Admin\BooksController::delete/$1');

    $routes->get('loans', 'Admin\LoansController::index');
    $routes->post('loans/(:num)/return', 'Admin\LoansController::markReturned/$1');

    $routes->get('members', 'Admin\MembersController::index');
    $routes->get('members/(:num)/edit', 'Admin\MembersController::edit/$1');
    $routes->post('members/(:num)', 'Admin\MembersController::update/$1');

    $routes->get('users', 'Admin\UsersController::index');
    $routes->get('users/(:num)/edit', 'Admin\UsersController::edit/$1');
    $routes->post('users/(:num)', 'Admin\UsersController::update/$1');
    $routes->post('users/(:num)/toggle', 'Admin\UsersController::toggleActive/$1');
    $routes->post('users/(:num)/role', 'Admin\UsersController::setRole/$1');
});

$routes->group('', ['filter' => 'session'], static function($routes) {
    $routes->get('profile', 'ProfileController::index');
    $routes->post('profile', 'ProfileController::update');
    $routes->post('profile/avatar', 'ProfileController::uploadAvatar');
});

