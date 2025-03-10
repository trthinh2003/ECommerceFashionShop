<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DialogflowWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Received Webhook Request:', $request->all());

        $intent = $request->input('queryResult.intent.displayName');
        $parameters = $request->input('queryResult.parameters', []);

        if ($intent === "iProducts") {
            $productName = $parameters['product'] ?? '';

            if (empty($productName)) {
                return response()->json(["fulfillmentText" => "Có phải bạn muốn biết thông tin về \"" . $productName . "\"?"]);
            }

            $products = DB::table('products')
                ->where('product_name', 'LIKE', "%{$productName}%")
                ->select('product_name', 'price')
                ->get();

            if ($products->isNotEmpty()) {
                $responseText = "Sản phẩm bạn tìm thấy:\n";
                foreach ($products as $product) {
                    $responseText .= "- {$product->product_name} ({$product->price} VND)\n";
                }
            } else {
                $responseText = "Xin lỗi, tôi không tìm thấy sản phẩm phù hợp.";
            }

            return response()->json(["fulfillmentText" => $responseText]);
        }

        return response()->json(["fulfillmentText" => "Xin lỗi, tôi không hiểu yêu cầu của bạn."]);
    }
}
