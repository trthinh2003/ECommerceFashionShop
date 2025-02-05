<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//JWT: json web token
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('discount', [ApiController::class, 'discounts'])->name('api.discounts');
Route::get('discount/{id}', [ApiController::class, 'discount'])->name('api.discount');
Route::get('category', [ApiController::class, 'categories'])->name('api.categories');

Route::get('inventory', [ApiController::class, 'inventories'])->name('api.inventories');
