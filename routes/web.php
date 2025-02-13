<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\StaffController;
use App\Models\Staff;
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

Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/login', [AdminController::class, 'post_login'])->name('admin.post_login');

//Trang client
Route::group(['prefix' => '/'], function () {
    Route::get('/', [HomeController::class, 'home'])->name('sites.home');
});

//Trang admin
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::resources(
        [
            'category' => CategoryController::class,
            'discount' => DiscountController::class,
            'product' => ProductController::class,
            'order' => OrderController::class,
            'provider' => ProviderController::class,
            'inventory' => InventoryController::class,
            'staff' => StaffController::class
        ]
    );
    Route::get('/add_extra', [InventoryController::class, 'add_extra'])->name('inventory.add_extra');
    Route::post('/post_add_extra', [InventoryController::class, 'post_add_extra'])->name('inventory.post_add_extra');
    Route::get('/search_category', [CategoryController::class, 'search'])->name('category.search');
    Route::get('/search_discount', [DiscountController::class, 'search'])->name('discount.search');
    Route::get('/search_product', [ProductController::class, 'search'])->name('product.search');
    Route::get('/search_provider', [ProviderController::class, 'search'])->name('provider.search');
    Route::get('/search_staff', [StaffController::class, 'search'])->name('staff.search');
    Route::get('/search_inventory', [InventoryController::class, 'search'])->name('inventory.search');
});
