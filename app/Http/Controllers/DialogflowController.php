<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\DetectIntentRequest;
use Google\Cloud\Dialogflow\V2\Client\SessionsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DialogflowController extends Controller
{
    public function detectIntent(Request $request)
    {
        try {
            $projectId = 'chatbotlaravel';
            $text = $request->input('message');
            $sessionId = $request->input('session_id', session()->getId());

            // Đường dẫn credentials
            $credentialsPath = storage_path('app/dialogflow/chatbotlaravel-f149074225c4.json');
            if (!file_exists($credentialsPath)) {
                return response()->json(['message' => 'Lỗi: File credentials không tồn tại'], 500);
            }

            // Kết nối với Dialogflow
            $sessionClient = new SessionsClient(['credentials' => $credentialsPath]);
            $session = $sessionClient->sessionName($projectId, $sessionId);

            // Tạo truy vấn
            $textInput = new TextInput();
            $textInput->setText($text);
            $textInput->setLanguageCode('vi');

            $queryInput = new QueryInput();
            $queryInput->setText($textInput);

            $detectIntentRequest = new DetectIntentRequest();
            $detectIntentRequest->setSession($session);
            $detectIntentRequest->setQueryInput($queryInput);

            // Gửi yêu cầu và nhận phản hồi
            $response = $sessionClient->detectIntent($detectIntentRequest);
            $queryResult = $response->getQueryResult();
            $intent = $queryResult->getIntent()->getDisplayName();
            $parameters = json_decode($queryResult->getParameters()->serializeToJsonString(), true);

            // Ghi log intent và parameters để debug
            Log::info("📌 Intent xác định: " . $intent);
            Log::info("📌 Tham số nhận được:", $parameters);

            // Xử lý intent
            $replyMessage = $this->handleIntent($intent, $parameters);

            // Đóng session
            $sessionClient->close();

            return response()->json(['message' => $replyMessage]);
        } catch (\Exception $e) {
            Log::error("❌ Lỗi chatbot: " . $e->getMessage());
            return response()->json(['message' => 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại!'], 500);
        }
    }

    private function handleIntent($intent, $parameters)
    {
        switch ($intent) {
            case "aboutWeb":
                return "Shop chuyên bán quần áo nam nữ: áo, quần, váy, phụ kiện. Bạn cần tìm sản phẩm nào?";

            case "iProducts":
                Log::info("✅ Intent `iProducts` đã được kích hoạt.");
                return $this->searchProduct($parameters);

            default:
                Log::warning("⚠️ Không tìm thấy Intent phù hợp.");
                return "Xin lỗi, mình chưa hiểu câu hỏi của bạn. Bạn có thể thử lại không?";
        }
    }

    private function searchProduct($parameters)
    {
        $category = $parameters['product_category'] ?? '';
        $material = $parameters['material'] ?? '';
        $style = $parameters['style'] ?? '';
        $color = $parameters['color'] ?? '';
    
        // Tạo mảng các từ khóa để tìm kiếm trong tags
        $keywords = array_filter([$category, $material, $style, $color]);
    
        // Truy vấn sản phẩm
        $query = DB::table('products')
            ->leftJoin('discounts', 'products.discount_id', '=', 'discounts.id')
            ->select('product_name', 'price', 'image', 'slug', 'discount_id', 'percent_discount');
    
        if (!empty($keywords)) {
            foreach ($keywords as $keyword) {
                $query->orWhere('tags', 'LIKE', '%' . $keyword . '%');
            }
        }
    
        $products = $query->take(5)->get();
    
        if ($products->isNotEmpty()) {
            return $products->map(function ($product) {
                $price = $product->discount_id ?
                    $product->price - ($product->price * $product->percent_discount) :
                    $product->price;
    
                return "<a href='" . url('product/' . $product->slug) . "' style='display: flex; align-items: center; gap: 10px; padding: 5px; text-decoration: none; color: #333;'>
                            <img src='uploads/{$product->image}' width='50' height='50' style='border-radius: 5px;'>
                            <span>{$product->product_name} (" . number_format($price, 0, ',', '.') . " đ)</span>
                        </a>";
            })->implode("\n");
        } else {
            return "Không tìm thấy sản phẩm phù hợp. Bạn có thể thử tìm với từ khóa khác không?";
        }
    }
    
    
    
}
