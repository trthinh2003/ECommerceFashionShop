<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\DetectIntentRequest;
use Google\Cloud\Dialogflow\V2\Client\SessionsClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DialogflowController extends Controller
{
    public function detectIntent(Request $request)
    {
        $projectId = 'ct258laravelchatbot';
        $text = $request->input('message');
        $sessionId = $request->input('session_id', session()->getId());

        $credentialsPath = storage_path('app/dialogflow/ct258laravelchatbot-61b1d74e12de.json');

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

        $request = new DetectIntentRequest();
        $request->setSession($session);
        $request->setQueryInput($queryInput);

        $response = $sessionClient->detectIntent($request);
        $queryResult = $response->getQueryResult();
        $replyMessage = $queryResult->getFulfillmentText();

        $sessionClient->close();
        Log::info("Response từ Dialogflow: " . json_encode($queryResult));


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
