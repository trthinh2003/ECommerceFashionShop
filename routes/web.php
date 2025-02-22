<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DialogflowController;
use App\Models\Customer;
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

/* TRANG CLIENT */

Route::group(['prefix' => '/'], function () {
    Route::get('/', [HomeController::class, 'home'])->name('sites.home');

    // Xử lý đăng nhập user
    Route::get('/login_user', [CustomerController::class, 'loginUser'])->name('sites.login_user');
    Route::post('/login', [CustomerController::class, 'post_login'])->name('sites.post_login');
    Route::get('/register', [CustomerController::class, 'register'])->name('sites.register');
    Route::post('/register', [CustomerController::class, 'post_register'])->name('sites.post_register');

    Route::get('/shop', [HomeController::class, 'shop'])->name('sites.shop');
    Route::get('/cart', [HomeController::class, 'cart'])->name('sites.cart');
    Route::get('/aboutUs', [HomeController::class, 'aboutUs'])->name('sites.aboutUs');
    Route::get('/blogDetail', [HomeController::class, 'blogDetail'])->name('sites.blogDetail');
    Route::get('/shopDetail', [HomeController::class, 'shopDetail'])->name('sites.shopDetail');
    Route::get('/shoppingCart', [HomeController::class, 'shoppingCart'])->name('sites.shoppingCart');
    Route::get('/contact', [HomeController::class, 'contact'])->name('sites.contact');
    Route::get('/blog', [HomeController::class, 'blog'])->name('sites.blog');
    Route::get('/checkout', [HomeController::class, 'checkout'])->name('sites.checkout');
    Route::post('/chatbot', [DialogflowController::class, 'detectIntent']);
    Route::get('/product/{slug}', [HomeController::class, 'productDetail'])->name('sites.productDetail');
    Route::post('/payment', [CheckoutController::class, 'checkout'])->name('payment.checkout');
});

// Xử lý cart
Route::group(['prefix' => '/cart'], function(){
    Route::get('/', [CartController::class, 'cart'])->name('sites.cart');
    Route::get('/add/{product?}/{quantity?}', [CartController::class, 'add'])->name('sites.add');
    Route::get('/update/{id}/{quantity?}', [CartController::class, 'update'])->name('sites.update');
    Route::get('/remove/{id}', [CartController::class, 'remove'])->name('sites.remove');
    Route::get('/clear', [CartController::class, 'clear']) ->name('sites.clear');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('sites.checkout');
    Route::post('/update-cart-session', [CartController::class, 'updateCartSession'])->name('sites.updateCartSession');
    Route::post('/create-percent-discount-session', [CartController::class, 'createPercentDiscountSession'])->name('sites.createPercentDiscountSession');
    Route::resources(
        [
            'order' => OrderController::class,
        ]
    );
});

/* TRANG ADMIN */
Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/login', [AdminController::class, 'post_login'])->name('admin.post_login');
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::resources(
        [
            'category' => CategoryController::class,
            'discount' => DiscountController::class,
            'product' => ProductController::class,
            'provider' => ProviderController::class,
            'inventory' => InventoryController::class,
            'staff' => StaffController::class
        ]
    );
    Route::put('/staff/{staff}/update', [StaffController::class, 'update_staff'])->name('staff.update_staff');
    Route::get('/add_extra', [InventoryController::class, 'add_extra'])->name('inventory.add_extra');
    Route::post('/post_add_extra', [InventoryController::class, 'post_add_extra'])->name('inventory.post_add_extra');
    Route::get('/search_category', [CategoryController::class, 'search'])->name('category.search');
    Route::get('/search_discount', [DiscountController::class, 'search'])->name('discount.search');
    Route::get('/search_product', [ProductController::class, 'search'])->name('product.search');
    Route::get('/search_provider', [ProviderController::class, 'search'])->name('provider.search');
    Route::get('/search_staff', [StaffController::class, 'search'])->name('staff.search');
    Route::get('/search_inventory', [InventoryController::class, 'search'])->name('inventory.search');
    Route::get('/profile', [StaffController::class, 'profile'])->name('staff.profile');
});
