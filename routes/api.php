<?php

use Illuminate\Support\Facades\Route;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('user/login', 'PassportController@login');
Route::post('user', 'PassportController@create');
Route::get('unauthorized', 'PassportController@unauthorized');

Route::get('images', 'ImageController@index');
Route::post('images', 'ImageController@upload');
Route::post("images/{id}", "ImageController@destroy");
//News
Route::resource('news', 'News\NewsController');
Route::resource('newsCategories', 'News\LookupsNewsCategoriesController');

//Cars
Route::resource('cars', 'Cars\CarsController');
Route::resource('carsCategories', 'Cars\CarsCategoriesLookupsController');

Route::get(
  'ProductByUserID/{id}',
  'Products\ProductsController@ProductByUserID'
);

Route::middleware('auth:api')->group(function () {
  Route::get('users', 'PassportController@index');
  Route::get('user', 'PassportController@show');
  Route::get('user/{id}', 'PassportController@UserByID');
  Route::put('user/{id}', 'PassportController@update');
  Route::delete('user/{id}', 'PassportController@destroy');
  Route::post('logout', 'PassportController@logoutApi');

  Route::resource('section', 'Sections\SectionsController');
  Route::resource('categories', 'Categories\CategoriesController');
  Route::get(
    'CatBySectionID/{id}',
    'Categories\CategoriesController@CatBySectionID'
  );
  Route::resource('attributes', 'Categories\CategoriesAttributesController');
  Route::resource('products', 'Products\ProductsController');
  Route::get(
    'ProductByAuthUserID',
    'Products\ProductsController@ProductByAuthUserID'
  );
  Route::resource(
    'products/variations',
    'Products\ProductsVariationsController'
  );
});
