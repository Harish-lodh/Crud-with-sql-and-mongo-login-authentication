<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/save', 'Home::insert');
$routes->post('/check', 'Home::login');
$routes->get('/details', 'Home::details');

$routes->get('/getUserById/(:num)', 'Home::getUserById/$1');
$routes->post('/update/(:num)', 'Home::update/$1');
$routes->get('/delete/(:num)', 'Home::delete/$1');

$routes->get('/logout', 'Home::logout');


//select2
$routes->post('/search-users', 'Home::details');



//dowload
$routes->get('/download-csv', 'Home::download');


//.upload
$routes->get('upload', 'Home::index');
$routes->post('upload/save', 'Home::save');


