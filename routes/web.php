<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', "BackEnd\DashboardController@index");
//products
Route::get('/backend/product/index', "Backend\ProductsController@index");

Route::get('/backend/product/create', "Backend\ProductsController@create");

Route::get('/backend/product/edit/{id}', "Backend\ProductsController@edit");

// cập nhật sản phẩm
Route::post('/backend/product/update/{id}', "Backend\ProductsController@update");

Route::get('/backend/product/delete/{id}', "Backend\ProductsController@delete");

// xóa sản phẩm
Route::post('/backend/product/destroy/{id}', "Backend\ProductsController@destroy");

Route::post('/backend/product/store',"Backend\ProductsController@store");

// end products

//category

Route::get('/backend/category/index', "Backend\CategoryController@index");

Route::get('/backend/category/create', "Backend\CategoryController@create");

Route::get('/backend/category/edit/{id}', "Backend\CategoryController@edit");

// cập nhật sản phẩm
Route::post('/backend/category/update/{id}', "Backend\CategoryController@update");

Route::get('/backend/category/delete/{id}', "Backend\CategoryController@delete");

// xóa sản phẩm
Route::post('/backend/category/destroy/{id}', "Backend\CategoryController@destroy");


Route::post('/backend/category/store',"Backend\CategoryController@store");
//end category
