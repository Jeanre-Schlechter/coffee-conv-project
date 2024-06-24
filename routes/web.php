<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome2');
});

Route::get('/home', function () {
    return view('welcome2');
});

Route::get('/please', 'App\Http\Controllers\TestController@test');

Route::post('/register', 'App\Http\Controllers\TestController@register');

Route::post('/logout', 'App\Http\Controllers\TestController@logout');
Route::get('/login', 'App\Http\Controllers\TestController@testLogin');
Route::post('/login', 'App\Http\Controllers\TestController@login');

Route::get('/filter-products', 'App\Http\Controllers\TestController@getFilteredProducts');

Route::get('/wishlist', 'App\Http\Controllers\TestController@getUserCart');
Route::get('/admin', 'App\Http\Controllers\TestController@admin');

Route::post('/user', 'App\Http\Controllers\TestController@register');
Route::post('/category', 'App\Http\Controllers\AdminController@addNewCategory');
Route::post('/product', 'App\Http\Controllers\AdminController@addNewProduct');

Route::put('/user/{userId}', 'App\Http\Controllers\AdminController@editUser');
Route::put('/category/{categoryId}', 'App\Http\Controllers\AdminController@editCategory');
Route::put('/product/{productId}', 'App\Http\Controllers\AdminController@editProduct');

Route::delete('/user/{userId}', 'App\Http\Controllers\AdminController@deleteUser');
Route::delete('/category/{categoryId}', 'App\Http\Controllers\AdminController@deleteCategory');
Route::delete('/product/{productId}', 'App\Http\Controllers\AdminController@deleteProduct');

Route::post('/cart/{productId}', 'App\Http\Controllers\TestController@addProductToUserCart');
Route::delete('/cart/{productId}', 'App\Http\Controllers\TestController@removeProductFromUserCart');

Route::get('/purchase/cart/', 'App\Http\Controllers\TestController@getUserCart');

Route::get('/purchase', function () {
    return view('purchase');
});

Route::post('/purchase/cart/pay', 'App\Http\Controllers\TestController@payUserCart');

// Route::get('/admin', function () {
//     return view('admin');
// });