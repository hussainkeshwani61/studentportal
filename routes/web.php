<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', 'App\Http\Controllers\Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login');
Route::post('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::get('/register', 'App\Http\Controllers\Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@register')->name('register_submit');
Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index')->name('dashboard');
Route::post('/enroll', 'App\Http\Controllers\DashboardController@enroll')->name('enroll');
Route::get('/graduation', 'App\Http\Controllers\DashboardController@graduation')->name('graduation');
Route::get('/profile', 'App\Http\Controllers\DashboardController@profile')->name('profile');
Route::post('/profile/update', 'App\Http\Controllers\DashboardController@updateProfile')->name('profile.update');