<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\ProductsController;
use App\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return view('welcome');
// });

// Frontend Routes
Route::get('/', 'IndexController@index');
Route::get('products/{url}', 'ProductsController@products');
Route::get('product/{id}', 'ProductsController@product');
Route::get('/get-product-price', 'ProductsController@getProductPrice');



// Backend Routes
Route::match(['get', 'post'],'/admin', 'AdminController@login');
Route::get('/logout', 'AdminController@logout');
Auth::routes();
Route::group(['middleware'=>['auth']], function(){
     Route::get('admin/dashboard', 'AdminController@dashboard');
     Route::get('admin/settings', 'AdminController@settings');
     Route::get('admin/check-pwd', 'AdminController@chkPassword');
     Route::match(['get', 'post'],'/admin/update-pwd', 'AdminController@updatePassword');
     Route::match(['get','post'], '/admin/add-category', 'CategoryController@addCategory');
     Route::get('/admin/view-categories', 'CategoryController@viewCategories');
     Route::match(['get','post'], '/admin/edit-category/{id}', 'CategoryController@editCategory');
     Route::match(['get','post'], '/admin/delete-category/{id}', 'CategoryController@deleteCategory');
     Route::match(['get','post'], '/admin/add-product', 'ProductsController@addProduct');
     Route::match(['get','post'], '/admin/edit-product/{id}', 'ProductsController@editProduct');
     Route::get('/admin/view-products', 'ProductsController@viewProducts');
     Route::match('get', '/admin/delete-product/{id}', 'ProductsController@deleteProduct');
     Route::match('get', '/admin/delete-attribute/{id}', 'ProductsController@deleteAttribute');
     Route::get('/admin/delete-product-image/{id}', 'ProductsController@deleteProductImage');

    // Product Attribues
    Route::match(['get', 'post'], '/admin/add-attributes/{id}', 'ProductsController@addAttributes')->name('addAttribute');
});
Route::get('/home', 'HomeController@index')->name('home');




