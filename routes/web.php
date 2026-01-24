<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [HomeController::class, 'catalog'])->name('products.index');
Route::get('/product/{product:slug}', [HomeController::class, 'show'])->name('product.show');

Route::get('/articles', [App\Http\Controllers\Front\ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article:slug}', [App\Http\Controllers\Front\ArticleController::class, 'show'])->name('articles.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Track Order
Route::get('/track', [App\Http\Controllers\Front\TrackOrderController::class, 'index'])->name('track.index');
Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/dashboard', function () {
    return redirect('/admin');
})->name('dashboard');

Route::get('/order/track/{token}', [CartController::class, 'track'])->name('order.track');
Route::post('/order/confirm/{token}', [CartController::class, 'confirmReceive'])->name('order.confirm');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes (Deprecated in favor of Filament)
    // Route::prefix('admin')->name('admin.')->group(function () {
    //     Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    //     Route::resource('products', App\Http\Controllers\ProductController::class);
    //     Route::resource('orders', App\Http\Controllers\OrderController::class);
    //     Route::patch('orders/{order}/status', [App\Http\Controllers\OrderController::class, 'updateStatus'])->name('orders.update-status');
        
    //     // New Features
    //     Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    //     Route::resource('articles', App\Http\Controllers\Admin\ArticleController::class);
    //     Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'update', 'destroy']);
    // });
});

require __DIR__.'/auth.php';
