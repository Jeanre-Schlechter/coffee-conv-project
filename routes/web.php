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

Route::get('/wishlist', 'App\Http\Controllers\TestController@getUserWishlist');
Route::get('/admin', 'App\Http\Controllers\TestController@admin');
Route::post('/user', 'App\Http\Controllers\AdminController@addNewUser');
Route::post('/category', 'App\Http\Controllers\AdminController@addNewCategory');
Route::post('/product', 'App\Http\Controllers\AdminController@addNewProduct');

Route::put('/user/{userId}', 'App\Http\Controllers\AdminController@editUser');
Route::put('/category/{categoryId}', 'App\Http\Controllers\AdminController@editCategory');
Route::put('/product/{productId}', 'App\Http\Controllers\AdminController@editProduct');



// Route::get('/admin', function () {
//     return view('admin');
// });