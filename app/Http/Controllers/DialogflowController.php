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

        switch ($intent) {
            case "iProducts":
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

                        $replyMessage = "Một số sản phẩm mình tìm được dựa trên \"" . $productName . "\":</br>" . $productList . "Bạn muốn mình tư vấn chi tiết cho từng sản phẩm không?";
                    } else {
                        $replyMessage = "Xin lỗi, mình không tìm thấy sản phẩm phù hợp.";
                    }
                } else {
                    $replyMessage = "Bạn muốn tìm sản phẩm nào? Vui lòng nói rõ hơn.";
                }
                break;
            case "aboutWeb":
                $parameters = json_decode($queryResult->getParameters()->serializeToJsonString(), true);
                $response = $parameters['response'] ?? '';
                if (!empty($response)) {
                    $replyMessage = "Bên mình có bán các sản phẩm áo, từ áo vải linen, "
                    . "vải cotton cho đến vải thun,... quần thì có đa dạng kiểu và loại từ quần short đến quần dài, quần tây. "
                    . "Cho mình hỏi bạn đang quan tâm mẫu sản phẩm nào?";
                } else {
                    $replyMessage = "Đây là website TST Fashion Shop, web bên mình có đa dạng các mẫu sản phẩm quần áo và phụ kiện. "
                    . "Các sản phẩm quần áo đều có đủ cả. Bạn muốn mình tư vấn chi tiết hơn không?";
                }
                break;
            default:
                break;
        }

        $sessionClient->close();

        return response()->json([
            'message' => $replyMessage
        ]);
    }
}
