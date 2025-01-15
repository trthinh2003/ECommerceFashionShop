<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::resources(
        [
            'category' => CategoryController::class,
            'product' => ProductController::class
        ]
    );
    Route::get('/search', [CategoryController::class, 'search'])->name('category.search');
    Route::get('/search', [ProductController::class, 'search'])->name('product.search');
});
