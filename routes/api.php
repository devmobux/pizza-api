<?php

use Illuminate\Http\Request;
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

Route::controller(\App\Http\Controllers\AuthController::class)->prefix('auth')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::get('/users/profiles', 'profile');
        Route::get('/logout', 'logout');
        Route::get('/refresh', 'refresh');
    });
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::controller(\App\Http\Controllers\PizzaController::class)->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::post('/pizzas', 'createPizza');
        Route::put('/pizzas/{id}', 'updatePizza')->where('id', '[0-9]+');
        Route::delete('/pizzas/{id}', 'deletePizza')->where('id', '[0-9]+');
        Route::post('/pizzas/{pizzaId}/ingredients/{ingredientId}', 'addIngredientToPizza')->where(['pizzaId', '[0-9]+', 'ingredientId', '[0-9]+']);
        Route::delete('/pizzas/{pizzaId}/ingredients/{ingredientId}', 'deleteIngredientToPizza')->where(['pizzaId', '[0-9]+', 'ingredientId', '[0-9]+']);
    });
    Route::get('/pizzas', 'getPizzas');
    Route::get('/pizzas/{id}', 'getPizza');
});

Route::controller(\App\Http\Controllers\IngredientsController::class)->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::post('/ingredients', 'createIngredient');
        Route::put('/ingredients/{id}', 'updateIngredient')->where('id', '[0-9]+');
        Route::delete('/ingredients/{id}', 'deleteIngredient')->where('id', '[0-9]+');
    });
    Route::get('/ingredients', 'getIngredients');
    Route::get('/ingredients/{id}', 'getIngredient');
});

Route::controller(\App\Http\Controllers\PizzaIngredientController::class)->group(function () {

});
