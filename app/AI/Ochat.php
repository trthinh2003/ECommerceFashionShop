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

    // public function send(string $message)
    // {
    //     try {
    //         $sessionKey = 'chatbot_context';
    //         $context = session($sessionKey, []);

    //         // 1️⃣ Kiểm tra câu từ chối và ngôn ngữ không hợp lệ
    //         if (preg_match('/(không mua|không cần|hủy|thoát|bye)/i', $message)) {
    //             session()->forget($sessionKey);
    //             return "Cảm ơn bạn đã ghé thăm TST Fashion Shop! Nếu cần tư vấn thêm, hãy nhắn tin nhé! 😊";
    //         }

    //         if (preg_match('/[^a-zA-Z0-9À-Ỹà-ỹ\s]/u', $message)) {
    //             return "Xin lỗi, tôi không hiểu. Bạn có thể nhập lại không? 😊";
    //         }

    //         // 2️⃣ Nếu có context, tiếp tục lấy thông tin màu & size
    //         if (!empty($context)) {
    //             if (!isset($context['color'])) {
    //                 $context['color'] = $message;
    //                 session([$sessionKey => $context]);
    //                 return "Bạn muốn chọn size nào? (M, L, XL...)";
    //             }

    //             if (!isset($context['size'])) {
    //                 $context['size'] = $message;
    //                 session([$sessionKey => $context]);
    //             }

    //             // 3️⃣ Tìm sản phẩm phù hợp
    //             $products = DB::table('products')
    //                 ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
    //                 ->where('product_variants.color', $context['color'])
    //                 ->where('product_variants.size', $context['size'])
    //                 ->where('products.product_name', 'LIKE', '%' . $context['product'] . '%')
    //                 ->select('products.*', 'product_variants.color', 'product_variants.size', 'product_variants.stock')
    //                 ->limit(5)
    //                 ->get();

    //             session()->forget($sessionKey);

    //             if ($products->isNotEmpty()) {
    //                 $response = "🔹 Dưới đây là các sản phẩm phù hợp:</br></br>";
    //                 foreach ($products as $product) {
    //                     $response .= "🛍️ {$product->product_name} ({$product->color}, {$product->size})</br>";
    //                     $response .= "💰 Giá: " . number_format($product->price) . " đ</br>";
    //                     $response .= "📦 Kho: " . ($product->stock > 0 ? "{$product->stock} sản phẩm" : "Hết hàng") . "</br></br>";
    //                 }
    //                 return $response;
    //             }

    //             // 4️⃣ Nếu không có sản phẩm → Gợi ý từ AI Ollama
    //             $prompt = "Người dùng muốn '{$context['product']}' màu {$context['color']} size {$context['size']}, nhưng không có hàng. Hãy gợi ý sản phẩm tương tự từ TST Fashion Shop.";
    //             $result = Ollama::model('llama3.2')
    //                 ->prompt($prompt)
    //                 ->options(['temperature' => 0.8])
    //                 ->stream(false)
    //                 ->ask();

    //             return is_array($result) && isset($result['response']) ? $result['response'] : "Xin lỗi, hiện tại sản phẩm bạn cần không có sẵn. Bạn có muốn xem sản phẩm tương tự không?";
    //         }

    //         // 5️⃣ Nếu người dùng nhập tên sản phẩm
    //         if (preg_match('/(áo sơ mi|áo thun|quần|giày|mũ|hoodie|váy)/i', $message)) {
    //             session([$sessionKey => ['product' => $message]]);
    //             return "Bạn muốn sản phẩm có màu gì?";
    //         }

    //         // 6️⃣ Gọi Ollama để phản hồi chung
    //         $result = Ollama::model('llama3.2')
    //             ->prompt("Người dùng hỏi: '$message'. Hãy phản hồi thông tin sản phẩm thời trang phù hợp từ TST Fashion Shop.")
    //             ->options(['temperature' => 0.8])
    //             ->stream(false)
    //             ->ask();

    //         return is_array($result) && isset($result['response']) ? $result['response'] : "Hiện tôi chưa có thông tin phù hợp, bạn có thể hỏi về sản phẩm thời trang nhé! 😊";
    //     } catch (Exception $e) {
    //         Log::error("Chatbot Error: " . $e->getMessage());
    //         return "Đã xảy ra lỗi: " . $e->getMessage();
    //     }
    // }

    public function send(string $message)
    {
        try {
            $sessionKey = 'chatbot_context';
            $context = session($sessionKey, []);
            $message = mb_strtolower(trim($message));

            $handlers = [
                'handleGreeting' => [$message, $sessionKey],
                'handleExit' => [$message, $sessionKey],
                'handleProductSuggestion' => [$message, $sessionKey],
                'handleProductQuery' => [$message, $sessionKey],
                'handleProductColor' => [$message, $sessionKey]
            ];

            foreach ($handlers as $method => $params) {
                if ($response = $this->$method(...$params)) return $response;
            }

            if (!empty($context)) {
                return $this->handleConversation($context, $message, $sessionKey);
            }

            return $this->callOllama($message);
        } catch (Exception $e) {
            Log::error("Chatbot Error: " . $e->getMessage());
            return "Xin lỗi, đã xảy ra lỗi. Vui lòng thử lại!";
        }
    }

    private function handleGreeting($message, $sessionKey)
    {
        if (preg_match('/\b(chào|xin chào|hi|hello)\b/i', $message)) {
            session()->forget($sessionKey);
            return "Xin chào! Cửa hàng TST Fashion Shop có thể giúp gì cho bạn hôm nay? 😊";
        }
        return null;
    }

    private function handleExit($message, $sessionKey)
    {
        if (preg_match('/\b(không mua|thoát|hủy|bye|tạm biệt|ko mua)\b/i', $message)) {
            session()->forget($sessionKey);
            return "Cảm ơn bạn đã ghé thăm TST Fashion Shop! Nếu cần tư vấn thêm, hãy nhắn tin nhé! 😊";
        }
        return null;
    }

    private function handleProductSuggestion($message)
    {
        if (preg_match('/\b(gợi ý vài sản phẩm|sản phẩm nổi bật|có gì hot|vài cái sản phẩm đi|gợi ý)\b/i', $message)) {
            return $this->getProductList();
        }
        return null;
    }

    private function handleProductQuery($message, $sessionKey)
    {
        $categories = [
            'áo thun' => 'áo thun',
            't-shirt' => 'áo thun',
            'áo sơ mi' => 'áo sơ mi',
            'áo hoodie' => 'áo hoodie',
            'quần' => 'quần',
            'quần jean' => 'quần jean',
            'quần hoodie' => 'quần hoodie',
            'giày' => 'giày',
            'sneaker' => 'giày',
            'mũ' => 'mũ',
            'hoodie' => 'hoodie',
            'váy' => 'váy'
        ];

        foreach ($categories as $keyword => $category) {
            if (stripos($message, $keyword) !== false) {
                Log::info("Đã nhận diện loại sản phẩm: " . $category);
                return $this->queryProductsByType($category, $sessionKey);
            }
        }
        return null;
    }

    private function handleProductColor($message, $sessionKey)
    {
        $colors = [
            'đen' => 'đen',
            'trắng' => 'trắng',
            'xanh' => 'xanh',
            'đỏ' => 'đỏ',
            'vàng' => 'vàng',
            'tím' => 'tím',
            'hồng' => 'hồng',
            'xám' => 'xám',
            'nâu' => 'nâu'
        ];
        foreach ($colors as $keyword => $color) {
            if (stripos($message, $keyword) !== false) {
                Log::info("Đã nhận diện màu sản phẩm: " . $color);
                return $this->queryProductsByType($color, $sessionKey);
            }
        }
        return null;
    }


    private function queryProductsByType($category, $sessionKey)
    {
        $products = $this->fetchProducts(['category' => $category], 5);
        if ($products->isNotEmpty()) {
            session([$sessionKey => ['product' => $category, 'step' => 'awaiting_color']]);
            return $this->formatProductResponse($products, "🔹 Đây là một số mẫu $category ở bên mình:") . "</br>Bạn cần mình tư vấn gì thêm không?";
        }
        return "Hiện tại chúng tôi chưa có $category trong kho. Bạn có muốn tìm sản phẩm khác không?";
    }

    private function fetchProducts(array $filters, int $limit = 5)
    {
        try {
            $query = DB::table('products')
                ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->select('products.*', 'product_variants.color', 'categories.category_name', 'product_variants.size', 'product_variants.stock')
                ->where('product_variants.stock', '>', 0);

            if (!empty($filters['category'])) {
                Log::info("Truy vấn sản phẩm thuộc danh mục: " . $filters['category']);
                Log::info("Truy vấn SQL: " . $query->toSql());
                Log::info("Tham số: ", $query->getBindings());
                $query->where('categories.category_name', 'LIKE', "%{$filters['category']}%")
                      ->orWhere('products.product_name', 'LIKE', "%{$filters['category']}%");
            }

            return $query->limit($limit)->get() ?? collect();
        } catch (Exception $e) {
            Log::error("Lỗi truy vấn sản phẩm: " . $e->getMessage());
            return collect();
        }
    }

    private function getProductList()
    {
        $products = $this->fetchProducts([], 5);
        if ($products->isNotEmpty()) {
            return $this->formatProductResponse($products, "🌟 Một số sản phẩm nổi bật mình tìm thấy:");
        }
        return "Hiện tại chưa có sản phẩm nào nổi bật. Bạn muốn tìm sản phẩm cụ thể nào không?";
    }

    private function handleConversation($context, $message, $sessionKey)
    {
        if ($context['step'] === 'awaiting_color') {
            $validColors = ['đen', 'trắng', 'xanh', 'đỏ', 'vàng', 'tím', 'hồng', 'xám', 'nâu'];
            if (in_array($message, $validColors)) {
                $context['color'] = $message;
                $context['step'] = 'checking_stock';
                session([$sessionKey => $context]);

                return "Bạn muốn chọn màu $message đúng không? Hãy để mình kiểm tra kho hàng nhé!";
            }
            return "Mình chưa nhận diện được màu này. Bạn có thể chọn màu như: " . implode(', ', $validColors) . " không?";
        }
    }

    private function formatProductResponse($products, $title)
    {
        $response = "$title</br></br>";
        foreach ($products as $product) {
            $response .= "🛍️ <b>{$product->product_name}</b> ({$product->color}, {$product->size})</br>";
            $response .= "💰 Giá: <b>" . number_format($product->price) . " đ</b></br>";
            if (!empty($product->image)) {
                $response .= "<img src='" . asset("uploads/{$product->image}") . "' alt='{$product->product_name}' style='max-width: 100px;'/><br>";
            }
            $response .= "📦 Số lượng còn: " . ($product->stock > 0 ? "{$product->stock} sản phẩm" : "Hết hàng") . "</br></br>";
        }
        return $response;
    }


    private function callOllama($message)
    {
        return Ollama::model('llama3.2')
            ->prompt("
                Bạn đang đóng vai chatbot bán hàng cho TST Fashion Shop.
                - Nếu khách hỏi về sản phẩm, trước tiên kiểm tra trong database.
                - Nếu không tìm thấy sản phẩm, hãy đề xuất một số mặt hàng có sẵn (Chỉ đề xuất áo hoặc quần thôi).
                - Nếu khách hỏi ngoài phạm vi, hãy trả lời lịch sự và khuyến khích họ mua sắm.
                - Không đưa thông tin ngoài về cửa hàng.

                Người dùng hỏi: '$message'.
            ")
            ->options(['temperature' => 0.7])
            ->stream(false)
            ->ask()['response'] ?? "Mình chưa hiểu lắm, bạn có thể cung cấp thêm chi tiết không? 😊";
    }
}
