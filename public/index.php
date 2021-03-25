<?php

use Router\Router;
use App\Exceptions\NotFoundException;

require '../vendor/autoload.php';

//constantes pour les vues et les scripts
define('VIEWS', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);
define('SCRIPTS', dirname($_SERVER['SCRIPT_NAME']) . DIRECTORY_SEPARATOR);

//constantes pour acceder a la BDD
define('DB_NAME', 'ma-27_yussefham_YHTecnologies');
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PWD', '');

//creation de de la route a execute par rapport a l'url
$router = new Router($_GET['url']);

// route pour la page de connection 
/* Se conneter */
$router->get('/login', 'App\Controllers\UserController@login');
$router->post('/login', 'App\Controllers\UserController@loginPost');
/* S'inscrire */
$router->get('/signin', 'App\Controllers\UserController@signin');
$router->post('/signin', 'App\Controllers\UserController@signinPost');
/* Se deconnecter */
$router->get('/logout', 'App\Controllers\UserController@logout');

///////////////////COTE ADMINISTRITEUR////////////////////////

// route pour la page d'accueil 
$router->get('/admin/home', 'App\Controllers\SiteController@home');

// route pour la page des produits
/* Creation */
$router->get('/admin/product/create', 'App\Controllers\Admin\ProductController@create');
$router->post('/admin/product/create', 'App\Controllers\Admin\ProductController@createPost');
/* Modification */
$router->get('/admin/product/edit/:id', 'App\Controllers\Admin\ProductController@edit');
$router->post('/admin/product/edit/:id', 'App\Controllers\Admin\ProductController@update');
/* Affichage */
$router->get('/admin/product', 'App\Controllers\Admin\ProductController@index');
$router->get('/admin/product/show/:id', 'App\Controllers\Admin\ProductController@show');
/* Suppression */
$router->post('/admin/product/destroy/:id', 'App\Controllers\Admin\ProductController@destroy');

// route pour la page des categories
/* Creation */
$router->get('/admin/category/create', 'App\Controllers\Admin\CategoryController@create');
$router->post('/admin/category/create', 'App\Controllers\Admin\CategoryController@createPost');
/* Modification */
$router->get('/admin/category/edit/:id', 'App\Controllers\Admin\CategoryController@edit');
$router->post('/admin/category/edit/:id', 'App\Controllers\Admin\CategoryController@update');
/* Affichage */
$router->get('/admin/category', 'App\Controllers\Admin\CategoryController@index');
$router->get('/admin/category/show/:id', 'App\Controllers\Admin\CategoryController@show');
/* Suppression */
$router->post('/admin/category/destroy/:id', 'App\Controllers\Admin\CategoryController@destroy');

// route pour la page d'affichage des commandes
/* Affichage */
$router->get('/admin/order', 'App\Controllers\Admin\OrdersController@index');
$router->get('/admin/order/show/:id', 'App\Controllers\Admin\OrdersController@show');
/* Modification */
$router->get('/admin/order/edit/:id', 'App\Controllers\Admin\OrdersController@edit');
$router->post('/admin/order/edit/:id', 'App\Controllers\Admin\OrdersController@update');

// route pour la page d'affichage des avis des client sur les produits
/* Affichage */
$router->get('/admin/rating', 'App\Controllers\Admin\RatingController@index');
/* Modification */
$router->get('/admin/rating/edit/:id', 'App\Controllers\Admin\RatingController@edit');
$router->post('/admin/rating/edit/:id', 'App\Controllers\Admin\RatingController@update');
/* Suppression */
$router->post('/admin/rating/destroy/:id', 'App\Controllers\Admin\RatingController@destroy');

// route pour la page des client
/*Creation */
$router->get('/admin/users/create', 'App\Controllers\Admin\UsersController@create');
$router->post('/admin/users/create', 'App\Controllers\Admin\UsersController@createPost');
/*Modification */
$router->get('/admin/users/edit/:id', 'App\Controllers\Admin\UsersController@edit');
$router->post('/admin/users/edit/:id', 'App\Controllers\Admin\UsersController@update');
/*Affichage */
$router->get('/admin/users', 'App\Controllers\Admin\UsersController@index');
$router->get('/admin/user/show/:id', 'App\Controllers\Admin\UsersController@show');
/*Suppression */
$router->post('/admin/users/destroy/:id', 'App\Controllers\Admin\UsersController@destroy');


///////////////////////////COTE FRONT////////////////////////////////


// route pour la page d'accueil
$router->get('/home', 'App\Controllers\SiteController@home');

// route pour la page d'affichage des produits
/*Affichage total */
$router->get('/product', 'App\Controllers\SiteController@indexP');
/*Affichage par categorie */
$router->get('/product/category/:id', 'App\Controllers\SiteController@indexPC');
/*Affichage unique */
$router->get('/product/show/:id', 'App\Controllers\SiteController@showP');

// route pour la page d'affichage des commandes
/*Affichage */
$router->get('/order', 'App\Controllers\SiteController@indexO');

//route pour la page des avis
/* Affichage */
$router->get('/rating', 'App\Controllers\SiteController@indexR');
/* Creation */
$router->get('/rating/create', 'App\Controllers\SiteController@create');
$router->post('/rating/create', 'App\Controllers\SiteController@createPost');

//route pour la page de conact
/*Creation */
$router->get('/contact', 'App\Controllers\SiteController@createContact');
$router->post('/contact', 'App\Controllers\SiteController@createPostContact');



//verification de l'existance de la route  
try {

    $router->run();
} catch (NotFoundException $e) {

    return $e->error404();
}
