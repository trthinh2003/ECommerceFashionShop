<?php

use App\Http\Controllers\DialogflowController;
use App\Http\Controllers\DialogflowWebhookController;
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
    return view('chatbot');
});

Route::post('/chatbot', [DialogflowController::class, 'detectIntent']);

Route::post('/webhook', [DialogflowWebhookController::class, 'handle']);
