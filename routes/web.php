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

  Route::get('/stockIn',[App\Http\Controllers\Admin\AdminController::class, 'stockIn'])->name('stockIn')->middleware(['auth:admin']);
  Route::post('/newStockIn',[App\Http\Controllers\Admin\AdminController::class, 'newStockIn'])->name('newStockIn')->middleware(['auth:admin']);


  Route::get('/products', [App\Http\Controllers\Admin\AdminController::class, 'products'])->name('products')->middleware(['auth:admin']);
  Route::post('/newProduct', [App\Http\Controllers\Admin\AdminController::class, 'newProduct'])->name('newProduct')->middleware(['auth:admin']);
  Route::post('/updateProduct', [App\Http\Controllers\Admin\AdminController::class, 'updateProduct'])->name('updateProduct')->middleware(['auth:admin']);
  Route::post('/deleteProduct', [App\Http\Controllers\Admin\AdminController::class, 'deleteProduct'])->name('deleteProduct')->middleware(['auth:admin']);

  Route::get('/recipes', [App\Http\Controllers\Admin\AdminController::class, 'recipes'])->name('recipes')->middleware(['auth:admin']);
  Route::post('/newRecipe', [App\Http\Controllers\Admin\AdminController::class, 'newRecipe'])->name('newRecipe')->middleware(['auth:admin']);
  Route::post('/updateRecipe', [App\Http\Controllers\Admin\AdminController::class, 'updateRecipe'])->name('updateRecipe')->middleware(['auth:admin']);
  Route::post('/deleteRecipe', [App\Http\Controllers\Admin\AdminController::class, 'deleteRecipe'])->name('deleteRecipe')->middleware(['auth:admin']);


  Route::get('/production', [App\Http\Controllers\Admin\AdminController::class, 'production'])->name('production')->middleware(['auth:admin']);
  Route::post('/produce', [App\Http\Controllers\Admin\AdminController::class, 'recordProduction'])->name('produce')->middleware(['auth:admin']);
  Route::get('/productionHistory', [App\Http\Controllers\Admin\AdminController::class, 'productionHistory'])->name('productionHistory')->middleware(['auth:admin']);

  Route::get('/adminList', [App\Http\Controllers\Admin\AdminController::class, 'adminList'])->name('admins')->middleware(['auth:admin']);
  Route::post('/newAdmin', [App\Http\Controllers\Admin\AdminController::class, 'newAdmin'])->name('newAdmin')->middleware(['auth:admin']);
  Route::post('/deleteAdmin', [App\Http\Controllers\Admin\AdminController::class, 'deleteAdmin'])->name('deleteAdmin')->middleware(['auth:admin']);

  Route::get('/staffList', [App\Http\Controllers\Admin\AdminController::class, 'staffList'])->name('staffs')->middleware(['auth:admin']);
  Route::post('/newStaff', [App\Http\Controllers\Admin\AdminController::class, 'newStaff'])->name('newStaff')->middleware(['auth:admin']);
  Route::post('/deleteStaff', [App\Http\Controllers\Admin\AdminController::class, 'deleteStaff'])->name('deleteStaff')->middleware(['auth:admin']);
  

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

