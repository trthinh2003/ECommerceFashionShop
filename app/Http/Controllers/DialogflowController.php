<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
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
        if (empty($parameters['category_id']) || empty($parameters['price_min']) || empty($parameters['price_max'])) {
            return "Vui lòng cung cấp đầy đủ thông tin như loại sản phẩm và mức giá.";
        }

        $category = $parameters['category_id'];
        $material = $parameters['material'] ?? '';
        $tags = $parameters['tags'] ?? '';
        $priceMin = $parameters['price_min'];
        $priceMax = $parameters['price_max'];
        $size = $parameters['size'] ?? '';
        $color = $parameters['color'] ?? '';
        $productName = $parameters['product_name'] ?? '';

        $keywords = array_filter([$category, $material, $tags, $size, $color, $productName]);

        $query = DB::table('products')
            ->leftJoin('productVariants', 'products.id', '=', 'productVariants.product_id')
            ->select('product_name', 'price', 'image', 'slug', 'size', 'color');

        $query->where('category_id', $category);

        if (!empty($productName)) {
            $query->where('product_name', 'LIKE', "%$productName%");
        }

        if (!empty($material)) {
            $query->where('material', 'LIKE', "%$material%");
        }

        if (!empty($size)) {
            $query->where('size', 'LIKE', "%$size%");
        }

        if (!empty($color)) {
            $query->where('color', 'LIKE', "%$color%");
        }

        if (!empty($keywords)) {
            foreach ($keywords as $keyword) {
                $query->orWhere('tags', 'LIKE', '%' . $keyword . '%');
            }
        }

        $query->whereBetween('price', [$priceMin, $priceMax]);

        $products = $query->take(5)->get();

        if ($products->isNotEmpty()) {
            return $products->map(function ($product) {
                return "<a href='" . url('product/' . $product->slug) . "' style='display: flex; align-items: center; gap: 10px; padding: 5px; text-decoration: none; color: #333;'>
                        <img src='uploads/{$product->image}' width='50' height='50' style='border-radius: 5px;'>
                        <span>{$product->product_name} (" . number_format($product->price, 0, ',', '.') . " đ)</span>
                    </a>";
            })->implode("\n");
        } else {
            return "Không tìm thấy sản phẩm phù hợp.";
        }
    }
}
