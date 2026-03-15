<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// redirect / to /login
$routes->get('/', function () {
    return redirect()->to('/login');
});

$routes->post('api/webhook', 'ChatBots::webhook');

$routes->get('lang/(:segment)', 'Language::switch/$1');

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


$routes->resource('categories', ['controller' => '\App\Controllers\Categories', 'filter' => 'session']);

$routes->get('products/(:any)/activate', '\App\Controllers\Products::activate/$1');
$routes->get('products/(:any)/deactivate', '\App\Controllers\Products::deactivate/$1');
$routes->resource('products', ['controller' => '\App\Controllers\Products', 'filter' => 'session']);

$routes->get('customers/(:any)/activate', '\App\Controllers\Customers::activate/$1');
$routes->get('customers/(:any)/deactivate', '\App\Controllers\Customers::deactivate/$1');
$routes->resource('customers/groups', ['controller' => '\App\Controllers\Groups', 'filter' => 'session']);
$routes->resource('customers', ['controller' => '\App\Controllers\Customers', 'filter' => 'session']);

$routes->resource('transactions', ['controller' => '\App\Controllers\Transactions', 'filter' => 'session']);

$routes->resource('broadcasts', ['controller' => '\App\Controllers\Broadcasts', 'filter' => 'session']);
$routes->resource('broadcast-variables', ['controller' => '\App\Controllers\BroadcastVariables', 'filter' => 'session']);

$routes->get('share-broadcasts/reshare/(:num)', '\App\Controllers\ShareBroadcasts::reshare/$1', ['filter' => 'session']);
$routes->get('share-broadcasts/get_variables/(:num)', '\App\Controllers\ShareBroadcasts::get_variables/$1', ['filter' => 'session']);
$routes->resource('share-broadcasts', ['controller' => '\App\Controllers\ShareBroadcasts', 'filter' => 'session']);

$routes->resource('auto-replies', ['controller' => '\App\Controllers\AutoReplies', 'filter' => 'session']);
$routes->get('chat-bots', '\App\Controllers\ChatBots::index', ['filter' => 'session']);

$routes->resource('stocks', ['controller' => '\App\Controllers\Stocks', 'filter' => 'session']);

$routes->post('sales', '\App\Controllers\Sales::create', ['filter' => 'session']);
$routes->get('sales', '\App\Controllers\Sales::new', ['filter' => 'session']);

$routes->get('reports', '\App\Controllers\Reports::index', ['filter' => 'session']);


service('auth')->routes($routes);
