<?php

use App\Controllers\Auth;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public routes (tanpa login)
$routes->get('/', 'Absen::index');
$routes->post('absen/absen', 'Absen::absensi');
$routes->get('dashboard', 'Absen::dashboard');

// Auth routes
$routes->get('/login', [Auth::class, 'login']);
$routes->post('/attempt-login', [Auth::class, 'attemptLogin']);
$routes->get('/logout', [Auth::class, 'logout']);


$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('siswa', 'Siswa::index');

    $routes->get('siswa/absensi/(:segment)', 'Siswa::absensi/$1');

    $routes->get('user/show/(:segment)', 'User::show/$1');
    $routes->post('user/newpass/(:segment)', 'User::newpass/$1');



    // User routes (hanya untuk admin)
    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('siswa/create', 'Siswa::create');
        $routes->post('siswa/save', 'Siswa::save');
        $routes->get('siswa/edit/(:any)', 'Siswa::edit/$1');
        $routes->post('siswa/update', 'Siswa::update');
        $routes->post('siswa/update/(:any)', 'Siswa::update/$1');
        $routes->get('siswa/delete/(:any)', 'Siswa::delete/$1');

        $routes->get('user', 'User::index');
        $routes->get('user/create', 'User::create');
        $routes->post('user/save', 'User::save');
        $routes->post('user/update', 'User::update');

        $routes->get('user/edit/(:segment)', 'User::edit/$1');
        $routes->post('user/update/(:segment)', 'User::update/$1');
        $routes->get('user/delete/(:segment)', 'User::destroy/$1');
        $routes->get('user/reset/(:any)', 'User::reset/$1');


        $routes->post('input', 'Absen::input');
        $routes->get('absensi', 'Absen::kehadiran');
        $routes->put('absensi/update', 'Absen::update');
        $routes->put('absensi/update/(:num)', 'Absen::update/$1');
        $routes->post('absensi/update', 'Absen::update'); // Fallback untuk form
    });
});
