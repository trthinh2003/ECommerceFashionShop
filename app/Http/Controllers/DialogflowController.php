<?php

namespace App\Http\Controllers;

// use Google\Cloud\Dialogflow\V2\SessionsClient;
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
                                    ->select('product_name', 'price')
                                    ->get()
                                    ->take(5);

                if ($matchedProducts->isNotEmpty()) {
                    $productList = $matchedProducts->map(function ($product) {
                        return "- {$product->product_name} ({$product->price} đ) </br>";
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
}
