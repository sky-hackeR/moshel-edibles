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

// Route::get('/', [App\Http\Controllers\Staff\Auth\LoginController::class, 'showLoginForm'])->name('login');

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [App\Http\Controllers\PageController::class, 'index'])->name('welcome');
Route::get('/products', [App\Http\Controllers\PageController::class, 'products'])->name('products');
Route::get('/about', [App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'contact'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

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

  Route::get('/profile', [App\Http\Controllers\Admin\AdminController::class, 'profile'])->name('profile')->middleware(['auth:admin']);
  Route::post('/updateProfile', [App\Http\Controllers\Admin\AdminController::class, 'updateProfile'])->name('updateProfile')->middleware(['auth:admin']);
  Route::post('/updatePassword', [App\Http\Controllers\Admin\AdminController::class, 'updatePassword'])->name('updatePassword')->middleware(['auth:admin']);
  
  Route::get('/home', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('home')->middleware(['auth:admin']);
  Route::get('/siteSettings', [App\Http\Controllers\Admin\AdminController::class, 'siteSettings'])->name('siteSettings')->middleware(['auth:admin']);

  Route::get('/unitManagement', [App\Http\Controllers\Admin\UnitController::class, 'unitManagement'])->name('unitManagement')->middleware(['auth:admin']);
  Route::post('/newUnit', [App\Http\Controllers\Admin\UnitController::class, 'newUnit'])->name('newUnit')->middleware(['auth:admin']);
  Route::post('/updateUnit', [App\Http\Controllers\Admin\UnitController::class, 'updateUnit'])->name('updateUnit')->middleware(['auth:admin']);
  Route::post('/deleteUnit', [App\Http\Controllers\Admin\UnitController::class, 'deleteUnit'])->name('deleteUnit')->middleware(['auth:admin']);

  Route::get('/ingredients', [App\Http\Controllers\Admin\IngredientController::class, 'ingredients'])->name('ingredients')->middleware(['auth:admin']);
  Route::post('/newIngredient', [App\Http\Controllers\Admin\IngredientController::class, 'newIngredient'])->name('newIngredient')->middleware(['auth:admin']);
  Route::post('/updateIngredient', [App\Http\Controllers\Admin\IngredientController::class, 'updateIngredient'])->name('updateIngredient')->middleware(['auth:admin']);
  Route::post('/deleteIngredient', [App\Http\Controllers\Admin\IngredientController::class, 'deleteIngredient'])->name('deleteIngredient')->middleware(['auth:admin']);

  Route::get('/inventory',[App\Http\Controllers\Admin\StockController::class, 'inventory'])->name('inventory')->middleware(['auth:admin']);

  Route::get('/stockIn',[App\Http\Controllers\Admin\StockController::class, 'stockIn'])->name('stockIn')->middleware(['auth:admin']);
  Route::post('/newStockIn',[App\Http\Controllers\Admin\StockController::class, 'newStockIn'])->name('newStockIn')->middleware(['auth:admin']);

  Route::get('/products', [App\Http\Controllers\Admin\ProductController::class, 'products'])->name('products')->middleware(['auth:admin']);
  Route::post('/newProduct', [App\Http\Controllers\Admin\ProductController::class, 'newProduct'])->name('newProduct')->middleware(['auth:admin']);
  Route::post('/updateProduct', [App\Http\Controllers\Admin\ProductController::class, 'updateProduct'])->name('updateProduct')->middleware(['auth:admin']);
  Route::post('/deleteProduct', [App\Http\Controllers\Admin\ProductController::class, 'deleteProduct'])->name('deleteProduct')->middleware(['auth:admin']);

  Route::get('/recipes', [App\Http\Controllers\Admin\RecipeController::class, 'recipes'])->name('recipes')->middleware(['auth:admin']);
  Route::post('/newRecipe', [App\Http\Controllers\Admin\RecipeController::class, 'newRecipe'])->name('newRecipe')->middleware(['auth:admin']);
  Route::post('/updateRecipe', [App\Http\Controllers\Admin\RecipeController::class, 'updateRecipe'])->name('updateRecipe')->middleware(['auth:admin']);
  Route::post('/deleteRecipe', [App\Http\Controllers\Admin\RecipeController::class, 'deleteRecipe'])->name('deleteRecipe')->middleware(['auth:admin']);

  Route::get('/production', [App\Http\Controllers\Admin\ProductionController::class, 'production'])->name('production')->middleware(['auth:admin']);
  Route::post('/produce', [App\Http\Controllers\Admin\ProductionController::class, 'recordProduction'])->name('produce')->middleware(['auth:admin']);
  Route::get('/productionHistory', [App\Http\Controllers\Admin\ProductionController::class, 'productionHistory'])->name('productionHistory')->middleware(['auth:admin']);

  Route::get('/pos', [App\Http\Controllers\Admin\POSController::class, 'pos'])->name('pos')->middleware(['auth:admin']);
  Route::post('/processSale', [App\Http\Controllers\Admin\POSController::class, 'processSale'])->name('processSale')->middleware(['auth:admin']);
  Route::get('/salesHistory', [App\Http\Controllers\Admin\POSController::class, 'salesHistory'])->name('salesHistory')->middleware(['auth:admin']);
  Route::post('/sales/void', [App\Http\Controllers\Admin\POSController::class, 'voidSale'])->name('sales.void')->middleware(['auth:admin']);
  Route::get('/sales/details/{id}', [App\Http\Controllers\Admin\POSController::class, 'getSaleDetails'])->name('sales.details')->middleware(['auth:admin']);
  
  Route::get('/adminList', [App\Http\Controllers\Admin\AdminController::class, 'adminList'])->name('admins')->middleware(['auth:admin']);
  Route::post('/newAdmin', [App\Http\Controllers\Admin\AdminController::class, 'newAdmin'])->name('newAdmin')->middleware(['auth:admin']);
  Route::post('/deleteAdmin', [App\Http\Controllers\Admin\AdminController::class, 'deleteAdmin'])->name('deleteAdmin')->middleware(['auth:admin']);

  Route::get('/staffList', [App\Http\Controllers\Admin\AdminController::class, 'staffList'])->name('staffs')->middleware(['auth:admin']);
  Route::post('/newStaff', [App\Http\Controllers\Admin\AdminController::class, 'newStaff'])->name('newStaff')->middleware(['auth:admin']);
  Route::post('/deleteStaff', [App\Http\Controllers\Admin\AdminController::class, 'deleteStaff'])->name('deleteStaff')->middleware(['auth:admin']);
  

  Route::get('/bulkOperations', [App\Http\Controllers\Admin\BulkController::class, 'bulkOperations'])->name('bulkOperations')->middleware(['auth:admin']);
  Route::get('/bulkOperations/template/{module}',[App\Http\Controllers\Admin\BulkController::class, 'downloadTemplate'])->middleware(['auth:admin']);
  
  
  Route::get('/unitManager', [App\Http\Controllers\Admin\BulkController::class, 'unitManager'])->name('unitManager')->middleware(['auth:admin']);
  Route::get('/systemSettings', [App\Http\Controllers\Admin\BulkController::class, 'systemSettings'])->name('systemSettings')->middleware(['auth:admin']);



});

Route::group(['prefix' => 'staff'], function () {
  Route::get('/', [App\Http\Controllers\Staff\Auth\LoginController::class, 'showLoginForm'])->name('staff.login');
  Route::get('/login', [App\Http\Controllers\Staff\Auth\LoginController::class, 'showLoginForm'])->name('login');
  Route::post('/login', [App\Http\Controllers\Staff\Auth\LoginController::class, 'login']);
  Route::post('/logout', [App\Http\Controllers\Staff\Auth\LoginController::class, 'logout'])->name('logout');

  // Route::get('/register', [App\Http\Controllers\Staff\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
  // Route::post('/register', [App\Http\Controllers\Staff\Auth\RegisterController::class, 'register']);
  
  Route::post('/password/email', [App\Http\Controllers\Staff\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.request');
  Route::post('/password/reset', [App\Http\Controllers\Staff\Auth\ResetPasswordController::class, 'reset'])->name('password.email');
  Route::get('/password/reset', [App\Http\Controllers\Staff\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.reset');
  Route::get('/password/reset/{token}', [App\Http\Controllers\Staff\Auth\ResetPasswordController::class, 'showResetForm']);

  Route::get('/home', [App\Http\Controllers\Staff\StaffController::class, 'index'])->name('home')->middleware(['auth:staff']);

  Route::get('/profile', [App\Http\Controllers\Staff\StaffController::class, 'profile'])->name('profile')->middleware(['auth:staff']);
  Route::post('/updateProfile', [App\Http\Controllers\Staff\StaffController::class, 'updateProfile'])->name('updateProfile')->middleware(['auth:staff']);
  Route::post('/updatePassword', [App\Http\Controllers\Staff\StaffController::class, 'updatePassword'])->name('updatePassword')->middleware(['auth:staff']);

  Route::get('/production', [App\Http\Controllers\Staff\ProductionController::class, 'production'])->name('production')->middleware(['auth:staff']);
  Route::post('/produce', [App\Http\Controllers\Staff\ProductionController::class, 'recordProduction'])->name('produce')->middleware(['auth:staff']);
  Route::get('/productionHistory', [App\Http\Controllers\Staff\ProductionController::class, 'productionHistory'])->name('productionHistory')->middleware(['auth:staff']);

  Route::get('/inventory',[App\Http\Controllers\Staff\InventoryController::class, 'inventory'])->name('inventory')->middleware(['auth:staff']);

  Route::get('/pos', [App\Http\Controllers\Staff\POSController::class, 'pos'])->name('pos')->middleware(['auth:staff']);
  Route::post('/processSale', [App\Http\Controllers\Staff\POSController::class, 'processSale'])->name('processSale')->middleware(['auth:staff']);
  Route::get('/salesHistory', [App\Http\Controllers\Staff\POSController::class, 'salesHistory'])->name('salesHistory')->middleware(['auth:staff']);
  Route::get('/sales/details/{id}', [App\Http\Controllers\Staff\POSController::class, 'getSaleDetails'])->name('sales.details')->middleware(['auth:staff']);
});

