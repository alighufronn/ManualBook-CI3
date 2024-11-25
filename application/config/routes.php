<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Manual Book
$route['default_controller'] = 'ManualBookController';
$route['manual-book'] = 'ManualBookController/index';
$route['detail/(:num)'] = 'ManualBookController/detail/$1';
$route['save-book'] = 'ManualBookController/save_mb';
$route['delete-multi/(:num)'] = 'ManualBookController/delete_multiple/$1';
$route['delete-img/(:num)'] = 'ManualBookController/delete_img/$1';
$route['update/(:num)'] = 'ManualBookController/update/$1';
$route['delete-imgs'] = 'ManualBookController/delete_imgs';
$route['delete-kategori/(:num)'] = 'ManualBookController/delete_kategori/$1';
$route['save-kategorimb'] = 'ManualBookController/save_kategorimb';


// Auth User
$route['login'] = 'AuthController/login';
$route['logout'] = 'AuthController/logout';
$route['login/login_process'] = 'AuthController/login_process';
$route['users'] = 'AuthController/user';

$route['users/add-user'] = 'AuthController/add';
$route['users/delete-user/(:num)'] = 'AuthController/delete/$1';
$route['users/edit-user'] = 'AuthController/edit';
$route['AuthController/getUserData'] = 'AuthController/getUserData';
