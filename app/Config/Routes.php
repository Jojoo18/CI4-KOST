<?php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ── Public Routes ─────────────────────────
$routes->get('/', 'Home::index');
$routes->get('katalog', 'PublicController::katalog');
$routes->get('pesan/(:num)', 'PublicController::pesan/$1');
$routes->post('pesan/store', 'PublicController::store');
$routes->post('review/store', 'ReviewController::store');   // ← baru

// ── Auth Routes ───────────────────────────
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::authenticate');
$routes->get('logout', 'Auth::logout');

// ── Admin Routes (dilindungi) ─────────────
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Dashboard::index');

    $routes->get('kamar', 'Kamar::index');
    $routes->get('kamar/create', 'Kamar::create');
    $routes->post('kamar/store', 'Kamar::store');
    $routes->get('kamar/edit/(:num)', 'Kamar::edit/$1');
    $routes->post('kamar/update/(:num)', 'Kamar::update/$1');
    $routes->get('kamar/delete/(:num)', 'Kamar::delete/$1');

    $routes->get('penghuni', 'Penghuni::index');
    $routes->get('penghuni/create', 'Penghuni::create');
    $routes->post('penghuni/store', 'Penghuni::store');
    $routes->get('penghuni/edit/(:num)', 'Penghuni::edit/$1');
    $routes->post('penghuni/update/(:num)', 'Penghuni::update/$1');
    $routes->get('penghuni/delete/(:num)', 'Penghuni::delete/$1');

    $routes->get('cashflow', 'Cashflow::index');
    $routes->get('cashflow/create', 'Cashflow::create');
    $routes->post('cashflow/store', 'Cashflow::store');
    $routes->get('cashflow/edit/(:num)', 'Cashflow::edit/$1');
    $routes->post('cashflow/update/(:num)', 'Cashflow::update/$1');
    $routes->get('cashflow/delete/(:num)', 'Cashflow::delete/$1');

    $routes->get('inventori', 'Inventori::index');
    $routes->get('inventori/create', 'Inventori::create');
    $routes->post('inventori/store', 'Inventori::store');
    $routes->get('inventori/edit/(:num)', 'Inventori::edit/$1');
    $routes->post('inventori/update/(:num)', 'Inventori::update/$1');
    $routes->get('inventori/delete/(:num)', 'Inventori::delete/$1');

    $routes->get('tagihan', 'Tagihan::index');
    $routes->get('tagihan/create', 'Tagihan::create');
    $routes->post('tagihan/store', 'Tagihan::store');
    $routes->get('tagihan/edit/(:num)', 'Tagihan::edit/$1');
    $routes->post('tagihan/update/(:num)', 'Tagihan::update/$1');
    $routes->get('tagihan/delete/(:num)', 'Tagihan::delete/$1');

    $routes->get('user', 'User::index');
    $routes->get('user/create', 'User::create');
    $routes->post('user/store', 'User::store');
    $routes->get('user/edit/(:num)', 'User::edit/$1');
    $routes->post('user/update/(:num)', 'User::update/$1');
    $routes->get('user/delete/(:num)', 'User::delete/$1');

    $routes->get('pemesanan', 'PemesananController::index');
    $routes->get('pemesanan/edit/(:num)', 'PemesananController::edit/$1');
    $routes->post('pemesanan/update/(:num)', 'PemesananController::update/$1');
    $routes->get('pemesanan/konfirmasi/(:num)', 'PemesananController::konfirmasiDp/$1');
    $routes->get('pemesanan/lunas/(:num)', 'PemesananController::lunas/$1');
    $routes->get('pemesanan/batal/(:num)', 'PemesananController::batal/$1');

    // Public route untuk lihat struk
    $routes->get('struk/(:num)', 'PublicController::struk/$1');

    // Admin route untuk download struk (dalam grup filter auth)
    $routes->get('pemesanan/struk/(:num)', 'PemesananController::struk/$1');

    $routes->get('download/bukti/(:any)', 'DownloadController::buktiBayar/$1');

    $routes->get('profil', 'Profil::index');
    $routes->post('profil/update', 'Profil::update');
});