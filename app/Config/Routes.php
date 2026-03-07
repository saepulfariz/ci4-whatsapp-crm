<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'session']);

$routes->group('/superadmin', ['filter' => 'session'], function ($routes) {
    // $routes->resource('users', ['controller' => '\App\Controllers\Superadmin\Users', 'filter' => 'group:superadmin,admin']);

    $routes->get('users/(:any)/banned', '\App\Controllers\Superadmin\Users::banned/$1');
    $routes->resource('users', ['controller' => '\App\Controllers\Superadmin\Users']);

    $routes->resource('auth-groups', ['controller' => '\App\Controllers\Superadmin\AuthGroups']);
    $routes->resource('auth-permissions', ['controller' => '\App\Controllers\Superadmin\AuthPermissions']);
    $routes->resource('auth-permissions-groups', ['controller' => '\App\Controllers\Superadmin\AuthPermissionsGroups']);
    $routes->resource('auth-groups-users', ['controller' => '\App\Controllers\Superadmin\AuthGroupsUsers']);
    $routes->resource('auth-permissions-users', ['controller' => '\App\Controllers\Superadmin\AuthPermissionsUsers']);

    $routes->get('auth-menus/order', '\App\Controllers\Superadmin\AuthMenus::order');
    $routes->get('auth-menus/(:any)/activate', '\App\Controllers\Superadmin\AuthMenus::activate/$1');
    $routes->get('auth-menus/(:any)/deactivate', '\App\Controllers\Superadmin\AuthMenus::deactivate/$1');
    $routes->post('auth-menus/updateOrder', '\App\Controllers\Superadmin\AuthMenus::updateOrder');
    $routes->resource('auth-menus', ['controller' => '\App\Controllers\Superadmin\AuthMenus']);
});

service('auth')->routes($routes);
