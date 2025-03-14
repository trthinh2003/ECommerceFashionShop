<?php
namespace App\AI;
use App\Models\Product;
use Cloudstudio\Ollama\Facades\Ollama;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Ochat
{

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

            // if (!empty($context)) {
            //     return $this->handleConversation($context, $message, $sessionKey);
            // }

            return $this->callOllama($message);
        } catch (Exception $e) {
            Log::error("Chatbot Error: " . $e->getMessage());
            return "Xin lỗi, đã xảy ra lỗi. Vui lòng thử lại!";
        }
    }

    private function handleGreeting($message, $sessionKey)
    {
        if (preg_match('/\b(chào|xin chào|hi|hello|2|chào bạn)\b/i', $message)) {
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
        if (preg_match('/\b(gợi ý vài sản phẩm|sản phẩm nổi bật|có gì hot|vài cái sản phẩm đi|gợi ý|đề xuất)\b/i', $message)) {
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
            'váy' => 'váy',
            'phụ kiện' => 'phụ kiện'
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
                - Nếu không tìm thấy sản phẩm, hãy đề xuất một số mặt hàng có sẵn (Chỉ đề xuất áo hoặc quần, phụ kiện thôi).
                - Nếu khách hỏi ngoài phạm vi, hãy trả lời lịch sự và khuyến khích họ mua sắm.
                - Không đưa thông tin ngoài về cửa hàng.

                Người dùng hỏi: '$message'.
            ")
            ->options(['temperature' => 0.7])
            ->stream(false)
            ->ask()['response'] ?? "Mình chưa hiểu lắm, bạn có thể cung cấp thêm chi tiết không? 😊";
    }
}
