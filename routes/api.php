<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::resource('categories', 'Category\CategoryController', ['except' => ['edit', 'create', 'destroy']]);
Route::resource('categories.books', 'Category\CategoryBookController', ['only' => ['index']]);

Route::resource('authors', 'Author\AuthorController', ['except' => ['edit', 'create', 'destroy']]);
Route::resource('authors.books', 'Author\AuthorBookController', ['only' => ['index']]);

Route::resource('books', 'Book\BookController', ['except' => ['edit', 'create', 'destroy']]);
Route::resource('books.rentals', 'Book\BookRentalController', ['only' => ['index']]);

Route::resource('users', 'User\UserController', ['except' => ['edit', 'create', 'destroy']]);
Route::resource('users.rentals', 'User\UserRentalController', ['except' => ['edit', 'create', 'destroy', 'show']]);


Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

Route::get('user/{email}', 'User\UserController@getUserByEmail');