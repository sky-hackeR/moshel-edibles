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

Route::get('/', [App\Http\Controllers\Staff\Auth\LoginController::class, 'showLoginForm'])->name('login');

Route::group(['prefix' => 'admin'], function () {
  Route::get('/', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
  Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
  Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');

  // Route::get('/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
  // Route::post('/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'register']);

  Route::post('/password/email', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
  Route::post('/password/reset', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'reset'])->name('password.email');
  Route::get('/password/reset', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
  Route::get('/password/reset/{token}', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'showResetForm']);

  Route::post('/updateSiteInfo', [App\Http\Controllers\Admin\AdminController::class, 'updateSiteInfo'])->name('updateSiteInfo')->middleware(['auth:admin']);
  
  Route::get('/home', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('home')->middleware(['auth:admin']);
  Route::get('/siteSettings', [App\Http\Controllers\Admin\AdminController::class, 'siteSettings'])->name('siteSettings')->middleware(['auth:admin']);

  Route::get('/unitManagement', [App\Http\Controllers\Admin\AdminController::class, 'unitManagement'])->name('unitManagement')->middleware(['auth:admin']);
  Route::post('/newUnit', [App\Http\Controllers\Admin\AdminController::class, 'newUnit'])->name('newUnit')->middleware(['auth:admin']);
  Route::post('/updateUnit', [App\Http\Controllers\Admin\AdminController::class, 'updateUnit'])->name('updateUnit')->middleware(['auth:admin']);
  Route::post('/deleteUnit', [App\Http\Controllers\Admin\AdminController::class, 'deleteUnit'])->name('deleteUnit')->middleware(['auth:admin']);

  Route::get('/ingredients', [App\Http\Controllers\Admin\AdminController::class, 'ingredients'])->name('ingredients')->middleware(['auth:admin']);
  Route::post('/newIngredient', [App\Http\Controllers\Admin\AdminController::class, 'newIngredient'])->name('newIngredient')->middleware(['auth:admin']);
  Route::post('/updateIngredient', [App\Http\Controllers\Admin\AdminController::class, 'updateIngredient'])->name('updateIngredient')->middleware(['auth:admin']);
  Route::post('/deleteIngredient', [App\Http\Controllers\Admin\AdminController::class, 'deleteIngredient'])->name('deleteIngredient')->middleware(['auth:admin']);

  Route::get('/inventory',[App\Http\Controllers\Admin\AdminController::class, 'inventory'])->name('inventory')->middleware(['auth:admin']);

});

Route::group(['prefix' => 'staff'], function () {
  Route::get('/login', [App\Http\Controllers\Staff\Auth\LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [App\Http\Controllers\Staff\Auth\LoginController::class, 'login']);
  Route::post('/logout', [App\Http\Controllers\Staff\Auth\LoginController::class, 'logout'])->name('logout');

  Route::get('/register', [App\Http\Controllers\Staff\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
  Route::post('/register', [App\Http\Controllers\Staff\Auth\RegisterController::class, 'register']);
  
  Route::post('/password/email', [App\Http\Controllers\Staff\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
  Route::post('/password/reset', [App\Http\Controllers\Staff\Auth\ResetPasswordController::class, 'reset'])->name('password.email');
  Route::get('/password/reset', [App\Http\Controllers\Staff\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
  Route::get('/password/reset/{token}', [App\Http\Controllers\Staff\Auth\ResetPasswordController::class, 'showResetForm']);
});

