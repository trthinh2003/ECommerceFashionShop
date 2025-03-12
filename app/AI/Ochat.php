<?php

// namespace App\AI;

// use Cloudstudio\Ollama\Facades\Ollama;

// class Ochat
// {

//     public function send(string $message)
//     {
//         $response = Ollama::model('llama3.2')
//             ->prompt($message)
//             ->options(['temperature' => 0.8])
//             // ->format('json')
//             ->stream(false)
//             ->ask();

//         return $response;
//     }
// }


namespace App\AI;

use App\Models\Product;
use Cloudstudio\Ollama\Facades\Ollama;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Ochat
{
    // public function send(string $message)
    // {
    //     $response = Ollama::model('llama3.2')
    //         ->prompt($message)
    //         ->options(['temperature' => 0.8])
    //         ->stream(false)
    //         ->ask();

    //     // Nếu $response là mảng, lấy giá trị của key 'response'
    //     if (is_array($response) && isset($response['response'])) {
    //         return $response['response'];
    //     }

    //     return 'Lỗi: Không thể lấy câu trả lời từ chatbot.';
    // }

    // public function send(string $message)
    // {
    //     // Tìm kiếm sản phẩm trong bảng products
    //     $product = Product::where('product_name', 'LIKE', '%' . $message . '%')->first();

    //     // Nếu tìm thấy sản phẩm
    //     if ($product) {
    //         return "Sản phẩm bạn tìm là: " . $product->product_name;
    //     }

    //     // Nếu không tìm thấy, dùng mô hình chatbot để phản hồi
    //     $response = Ollama::model('llama3.2')
    //         ->prompt($message)
    //         ->options(['temperature' => 0.8])
    //         ->stream(false)
    //         ->ask();

    //     return $response['response'] ?? 'Không thể xử lý yêu cầu.';
    // }

    public function send(string $message)
    {
        try {
            // Tìm kiếm tất cả các sản phẩm theo tên
            // $products = Product::where('product_name', 'LIKE', '%' . $message . '%')->get();
            $products = DB::table('products')->where('product_name', 'LIKE', '%' . $message . '%')->get();

            if ($products->isNotEmpty()) {
                $response = "";
                foreach ($products as $product) {
                    $response .= "Sản phẩm: " . $product->product_name . " ";
                    $response .= "Giá: " . number_format($product->price) . " VND ";
                    $response .= "Mô tả: " . $product->description . " ";
                }
            } else {
                // Nếu không tìm thấy sản phẩm, gọi chatbot như bình thường
                $result = Ollama::model('llama3.2')
                    ->prompt($message)
                    ->options(['temperature' => 0.8])
                    ->stream(false)
                    ->ask();

                // Kiểm tra kết quả trả về có phải là mảng và có chứa response hay không
                if (is_array($result) && isset($result['response'])) {
                    $response = $result['response'];
                } else {
                    $response = "Không có phản hồi từ chatbot!";
                }
            }

            return $response;
        } catch (Exception $e) {
            // Ghi log lỗi ra file
            Log::error("Chatbot Error: " . $e->getMessage());

            // Trả về lỗi để hiển thị
            return "Đã xảy ra lỗi: " . $e->getMessage();
        }
    }
}
