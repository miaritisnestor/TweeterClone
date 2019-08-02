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

//Navigation Routes
Route::get('/home', function () {
    return view('home');
})->name('home');



Route::get('/post', function () {
    return view('post');
})->name('post');

// Route::get('/profile', function () {
//     return view('profile');
// })->name('profile');

//Login - Register - Logout Routes
Route::post('/login', 'UserController@login')->name('login');
Route::post('/register', 'UserController@register')->name('register');
Route::get('/logout', 'UserController@logout')->name('logout');

//Post tweet route
Route::post('/save_post', 'PostController@savePost')->name('save_post');

//Profile page route
Route::get('/profile/{username}', 'UserController@profileInfo')->name('profile');

//Follow - Unfollow URL
Route::post('/follow','FollowController@followUser')->name('follow');
Route::post('/unfollow','FollowController@unfollowUser')->name('unfollow');

//Timeline Route
Route::get('/timeline', 'UserController@timeline')->name('timeline');

//Users List page route
Route::get('/list', 'UserController@usersList')->name('list');

