<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('productView');
    }
    return view('welcome');
});

// Shared Authenticated Routes (Both Admin and Normal User)
Route::middleware('auth')->group(function () {
    // User Product List & View Detail
    Route::get('/productView', [ProductController::class, 'productView'])->name('productView');
    Route::get('/product-view', [ProductController::class, 'productView']);
    Route::get('/products', [ProductController::class, 'productView'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin-Only Routes (Dashboard and Product Add / Edit / Update / Delete)
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');

    // Product CRUD
    Route::get('/product-form-view', [ProductController::class, 'productFormView'])->name('products.create');
    Route::post('/create-product', [ProductController::class, 'createProduct'])->name('products.store');

    Route::get('/edit-product/{id}', [ProductController::class, 'editProductView'])->name('products.edit');
    Route::match(['put', 'patch', 'post'], '/update-product/{id}', [ProductController::class, 'updateProduct'])->name('products.update');
    Route::delete('/delete-product/{id}', [ProductController::class, 'deleteProduct'])->name('products.destroy');
});

require __DIR__ . '/auth.php';
