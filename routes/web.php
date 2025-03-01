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
use App\Http\Controllers\FacebookAuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\WishListProductController;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

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
    Route::group(['prefix' => 'user'], function () {
        Route::get('/login', [CustomerController::class, 'login'])->name('user.login');
        Route::post('/login', [CustomerController::class, 'post_login'])->name('user.post_login');
        Route::get('/logout', [CustomerController::class, 'logout'])->name('user.logout')->middleware('auth:customer');
        Route::get('/register', [CustomerController::class, 'register'])->name('user.register');
        Route::post('/register', [CustomerController::class, 'post_register'])->name('user.post_register');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('user.profile')->middleware('auth:customer');
        Route::put('/profile/{customer}/update', [CustomerController::class, 'update_profile'])->name('user.update_profile');
        Route::post('/check-login', [CustomerController::class, 'checkLogin'])->name('user.checkLogin');

    });

    Route::get('/shop', [HomeController::class, 'shop'])->name('sites.shop');
    Route::get('/cart', [HomeController::class, 'cart'])->name('sites.cart');
    Route::get('/aboutUs', [HomeController::class, 'aboutUs'])->name('sites.aboutUs');
    Route::get('/blogDetail', [HomeController::class, 'blogDetail'])->name('sites.blogDetail');
    Route::get('/shopDetail', [HomeController::class, 'shopDetail'])->name('sites.shopDetail');
    Route::get('/shoppingCart', [HomeController::class, 'shoppingCart'])->name('sites.shoppingCart');
    Route::get('/contact', [HomeController::class, 'contact'])->name('sites.contact');
    Route::get('/blog', [HomeController::class, 'blog'])->name('sites.blog');
    Route::get('/product/{slug}', [HomeController::class, 'productDetail'])->name('sites.productDetail');
    // Xử lý danh sách yêu thích
    Route::get('/wishlist', [WishListProductController::class, 'index'])->name('sites.wishlist');
    Route::get('/add-to-wishlist/{product}', [WishListProductController::class, 'addToWishList'])->name('sites.addToWishList');
    Route::get('/remove-from-wishlist/{id}', [WishListProductController::class, 'removefromWishList'])->name('sites.removefromWishList');
    // Xử lý chatbot
    Route::post('/chatbot', [DialogflowController::class, 'detectIntent']);

    // Xử lý đơn hàng
    Route::get('/order-history', [CustomerController::class, 'getHistoryOrderOfCustomer'])->name('sites.getHistoryOrder');
    Route::get('/order-detail/{order}', [CustomerController::class, 'showOrderDetailOfCustomer'])->name('sites.showOrderDetailOfCustomer');
    Route::put('/cancel-order{id}', [CustomerController::class, 'cancelOrder'])->name('sites.cancelOrder');

    // Xuất hoá đơn PDF
    Route::get('/order/{id}/invoice', [OrderController::class, 'exportInvoice'])->name('order.invoice');

    // Xử lý thanh toán
    Route::get('/checkout', [HomeController::class, 'checkout'])->name('sites.checkout');
    Route::post('/payment', [CheckoutController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success', [HomeController::class, 'successPayment'])->name('sites.success.payment');
    // Routes xử lý callback từ các cổng thanh toán
    Route::get('/vnpay-return', [CheckoutController::class, 'vnpayReturn'])->name('payment.vnpay.return');
    Route::get('/momo-return', [CheckoutController::class, 'momoReturn'])->name('payment.momo.return');
    Route::get('/zalopay-return', [CheckoutController::class, 'zalopayReturn'])->name('payment.zalopay.return');
});

// Xử lý cart
Route::group(['prefix' => '/cart'], function () {
    Route::get('/', [CartController::class, 'cart'])->name('sites.cart');
    Route::get('/add/{product?}/{quantity?}', [CartController::class, 'add'])->name('sites.add');
    Route::post('/addToCart-from-product/{product?}/{quantity?}', [CartController::class, 'addToCartFromProduct'])->name('sites.addToCartFromProduct');
    Route::get('/update/{id}/{quantity?}', [CartController::class, 'update'])->name('sites.update');
    Route::get('/remove/{id}', [CartController::class, 'remove'])->name('sites.remove');
    Route::get('/clear', [CartController::class, 'clear'])->name('sites.clear');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('sites.checkout');
    Route::post('/update-cart-session', [CartController::class, 'updateCartSession'])->name('sites.updateCartSession');
    Route::post('/create-percent-discount-session', [CartController::class, 'createPercentDiscountSession'])->name('sites.createPercentDiscountSession');
    Route::resources(
        [
            'order' => OrderController::class,
        ]
    );
});

// Xử lý đăng nhập gg
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

// Xử lý đăng nhập fb
// Route::get('/auth/facebook', [FacebookAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
// Route::get('/auth/facebook/callback', [FacebookAuthController::class, 'handleFacebookCallback']);


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
    Route::get('/search_order', [OrderController::class, 'search'])->name('order.search');
    Route::get('/profile', [StaffController::class, 'profile'])->name('staff.profile');
    Route::get('/order-approval', [OrderController::class, 'orderApproval'])->name('order.approval');

    // Thong ke doanh thu va loi nhuan
    Route::group(['prefix' => '/revenue'], function () {
        Route::get('/day', [RevenueController::class, 'revenueDay'])->name('admin.revenueDay');
        Route::get('/month', [RevenueController::class, 'revenueMonth'])->name('admin.revenueMonth');
        Route::get('/year', [RevenueController::class, 'revenueYear'])->name('admin.revenueYear');
        Route::get('/profit', [RevenueController::class, 'profitYear'])->name('admin.profitYear');
    });
});
