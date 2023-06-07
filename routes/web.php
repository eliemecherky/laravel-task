<?php

use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Users
    Route::resource('users', UserController::class);
    Route::get('/users-list', [UserController::class, "getUsers"])->name('users.list');
    Route::delete('/delete-user', [UserController::class, 'delete'])->name('delete.user');

    // Categories
    Route::resource('categories', ProductCategoryController::class);
    Route::get('/categories-list', [ProductCategoryController::class, "getCategories"])->name('categories.list');
    Route::delete('/delete-category', [ProductCategoryController::class, 'delete'])->name('delete.category');

    // Products
    Route::resource('products', ProductController::class);
    Route::get('/products-list', [ProductController::class, "getProducts"])->name('products.list');
    Route::delete('/delete-product', [ProductController::class, 'delete'])->name('delete.product');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
