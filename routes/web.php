<?php
use Illuminate\Support\Facades\Route;
use App\AI\Ochat;
use Illuminate\Http\Request;

Route::get('/chatbot', function () {
    return view('chatbot');
});

Route::post('/chatbot', function (Request $request) {
    $message = $request->input('message');

    // Gọi hàm gửi tin nhắn từ Ochat
    $chatbot = new Ochat();
    $response = $chatbot->send($message);

    // Đảm bảo response trả về dưới dạng JSON
    return response()->json(['response' => $response]);
});

Route::get('/test-chatbot', function () {
    $chatbot = new Ochat();
    $response = $chatbot->send("Hello");
    dd($response);
});