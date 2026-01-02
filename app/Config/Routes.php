<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::login'); 
$routes->get('login', 'Auth::login'); 
$routes->post('login', 'Auth::doLogin');
$routes->get('logout', 'Auth::logout');
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Dashboard::index');
    $routes->group('', ['filter' => 'admin'], function($routes) {
        $routes->get('produk', 'ProdukController::index');
        $routes->post('produk/save', 'ProdukController::save');
        $routes->get('produk/getProduk/(:num)', 'ProdukController::getProduk/$1');
        $routes->get('produk/delete/(:num)', 'ProdukController::delete/$1');
    });
    $routes->get('transaksi', 'TransaksiController::index');
    $routes->post('transaksi/saveUser', 'TransaksiController::saveUser');
    $routes->post('transaksi/saveAdmin', 'TransaksiController::saveAdmin');
    $routes->get('transaksi/getTransaksi/(:num)', 'TransaksiController::getTransaksi/$1');
    $routes->get('transaksi/delete/(:num)', 'TransaksiController::delete/$1');
    $routes->get('transaksi/export', 'TransaksiController::export');
});
