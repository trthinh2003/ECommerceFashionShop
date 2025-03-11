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
            $text = $request->input('message', '');
            $sessionId = $request->input('session_id', session()->getId());

            $credentialsPath = storage_path('app/dialogflow/chatbotlaravel-f149074225c4.json');
            if (!file_exists($credentialsPath)) {
                return response()->json(['message' => 'Lỗi: File credentials không tồn tại'], 500);
            }

            // Kết nối với Dialogflow
            $sessionClient = new SessionsClient(['credentials' => $credentialsPath]);
            $session = $sessionClient->sessionName($projectId, $sessionId);

            // Tạo truy vấn Dialogflow
            $textInput = new TextInput();
            $textInput->setText($text);
            $textInput->setLanguageCode('vi');

            $queryInput = new QueryInput();
            $queryInput->setText($textInput);

            $detectIntentRequest = new DetectIntentRequest();
            $detectIntentRequest->setSession($session);
            $detectIntentRequest->setQueryInput($queryInput);

            // Gửi request và nhận kết quả
            $response = $sessionClient->detectIntent($detectIntentRequest);
            $queryResult = $response->getQueryResult();
            $intent = $queryResult->getIntent()->getDisplayName();
            $parameters = json_decode($queryResult->getParameters()->serializeToJsonString(), true);

            Log::info("📌 Intent xác định: " . $intent);
            Log::info("📌 Tham số nhận được:", $parameters);

            $replyMessage = $this->handleIntent($intent, $parameters);

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
                return "💡 Chào bạn! Shop chuyên bán quần áo nam nữ: áo, quần, váy, phụ kiện.
                        🎯 Chúng tôi luôn cập nhật mẫu mã mới nhất! Bạn đang tìm sản phẩm nào?";

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
        $productName = !empty($parameters['product_name']) ? implode(" ", $parameters['product_name']) : null;
        $tags = !empty($parameters['tags']) ? $parameters['tags'] : null;
        $material = !empty($parameters['material']) ? $parameters['material'] : null;
        $categoryId = !empty($parameters['category_id']) ? $parameters['category_id'] : null;

        DB::flushQueryLog(); // Xóa cache truy vấn cũ

        $query = DB::table('products')
            ->leftJoin('discounts', 'products.discount_id', '=', 'discounts.id')
            ->select(
                'products.product_name',
                'products.price',
                'products.image',
                'products.slug',
                'products.discount_id',
                'discounts.percent_discount'
            );

        // Áp dụng điều kiện nếu giá trị tồn tại
        if ($productName) {
            $query->where('products.product_name', 'LIKE', "%$productName%");
        }
        if ($categoryId) {
            $query->where('products.category_id', $categoryId);
        }
        if ($tags) {
            $query->where('products.tags', 'LIKE', "%$tags%");
        }
        if ($material) {
            $query->where('products.material', 'LIKE', "%$material%");
        }

        $products = $query->take(5)->get();

        if ($products->isNotEmpty()) {
            return $products->map(function ($product) {
                $price = $product->discount_id
                    ? $product->price - ($product->price * $product->percent_discount)
                    : $product->price;

                return "<a href='" . url('product/' . $product->slug) . "'
                            style='display: flex; align-items: center; gap: 10px; padding: 5px; text-decoration: none; color: #333;'>
                            <img src='uploads/{$product->image}' width='50' height='50' style='border-radius: 5px;'>
                            <span>{$product->product_name} (" . number_format($price, 0, ',', '.') . " đ)</span>
                        </a>";
            })->implode("\n");
        } else {
            return "Không tìm thấy sản phẩm phù hợp.";
        }
    }

}
