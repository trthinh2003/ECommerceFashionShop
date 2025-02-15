<?php

namespace App\Http\Controllers;

// use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\DetectIntentRequest;
use Google\Cloud\Dialogflow\V2\Client\SessionsClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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
        // Log::info('Request từ Dialogflow:', $request);
        return response()->json([
            
            'message' => $replyMessage
        ]);
    }
}
