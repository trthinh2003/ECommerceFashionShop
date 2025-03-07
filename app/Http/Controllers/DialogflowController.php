<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\DetectIntentRequest;
use Google\Cloud\Dialogflow\V2\Client\SessionsClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DialogflowController extends Controller
{
    public function detectIntent(Request $request)
    {
        $projectId = 'chatbotlaravel';
        $text = $request->input('message');
        $sessionId = $request->input('session_id', session()->getId());

        $credentialsPath = storage_path('app/dialogflow/chatbotlaravel-f149074225c4.json');

        if (!file_exists($credentialsPath)) {
            return response()->json(['error' => 'File credentials không tồn tại']);
        }

        $sessionClient = new SessionsClient([
            'credentials' => $credentialsPath
        ]);

        $session = $sessionClient->sessionName($projectId, $sessionId);

        $textInput = new TextInput();
        $textInput->setText($text);
        $textInput->setLanguageCode('vi');

        $queryInput = new QueryInput();
        $queryInput->setText($textInput);

        $detectIntentRequest = new DetectIntentRequest();
        $detectIntentRequest->setSession($session);
        $detectIntentRequest->setQueryInput($queryInput);

        $response = $sessionClient->detectIntent($detectIntentRequest);
        $queryResult = $response->getQueryResult();
        $intent = $queryResult->getIntent()->getDisplayName();
        $replyMessage = $queryResult->getFulfillmentText();

        //Intent iProducts
        if ($intent === "iProducts") {
            $parameters = json_decode($queryResult->getParameters()->serializeToJsonString(), true);
            $productName = $parameters['product'] ?? '';

            if (!empty($productName)) {
                $matchedProducts = DB::table('products')
                                    ->where('product_name', 'LIKE', "%{$productName}%")
                                    ->orWhere('description', 'LIKE', "%{$productName}%")
                                    ->orWhere('tags', 'LIKE', "%{$productName}%")
                                    ->select('product_name', 'price', 'image', 'slug')
                                    ->get()
                                    ->take(5);

                if ($matchedProducts->isNotEmpty()) {
                    $productList = $matchedProducts->map(function ($product) {
                        return "<a href='" . url('product/' . $product->slug) . "' 
                        style='cursor: pointer; display: flex; align-items: center; gap: 10px; padding: 5px; border-radius: 5px; transition: 0.3s; text-decoration: none; color: #333;'
                        onmouseover='this.style.backgroundColor=\"#f0f0f0\"; this.style.textDecoration=\"underline\";'
                        onmouseout='this.style.backgroundColor=\"#fff\"; this.style.textDecoration=\"none\";'>
                        <img src='uploads/{$product->image}' width='50' height='50' style='border-radius: 5px;'> 
                        <span>{$product->product_name} (" . number_format($product->price, 0, ',', '.') . " đ)</span>
                    </a><br>";
            
            
        })->implode("\n");

                    $replyMessage = "Các sản phẩm chúng tôi tìm được dựa trên \"" . $productName . "\":</br>" . $productList . "Bạn muốn chúng tôi tư vấn chi tiết cho từng sản phẩm không?";
                } else {
                    $replyMessage = "Xin lỗi, chúng tôi không tìm thấy sản phẩm phù hợp.";
                }
            } else {
                $replyMessage = "Bạn muốn tìm sản phẩm nào? Vui lòng nói rõ hơn.";
            }
        }

        $sessionClient->close();

        return response()->json([
            'message' => $replyMessage
        ]);
    }


    public function getProductInfo(Request $request) {
        Log::info('Webhook nhận request:', $request->all()); // Log dữ liệu từ Dialogflow
    
        $intent = $request->input('queryResult.intent.displayName');
    
        if ($intent === "AoThun") {
            $productName = $request->input('queryResult.parameters.product'); 
            
            Log::info("Sản phẩm tìm kiếm: " . $productName);
    
            $product = Product::where('product_name', 'like', "%$productName%")->first();
    
            if ($product) {
                $priceFormatted = number_format($product->price, 0, ',', '.'); // Format tiền VND
                $responseText = "Sản phẩm {$product->product_name} có giá {$priceFormatted} VNĐ. Bạn muốn đặt hàng không?";
            } else {
                $responseText = "Xin lỗi, shop không tìm thấy sản phẩm này. Bạn có thể thử tên khác không?";
            }
    
            Log::info("Phản hồi gửi về Dialogflow: " . $responseText);
            return response()->json(['fulfillment_text' => $responseText]);
        }
    
        return response()->json(['fulfillment_text' => "Mình chưa hiểu câu hỏi của bạn."]);
    }
    
}
