<?php

// namespace App\AI;

// use Cloudstudio\Ollama\Facades\Ollama;

// class Ochat
// {

//     public function send(string $message)
//     {
//         $response = Ollama::model('llama3.2')
//             ->prompt($message)
//             ->options(['temperature' => 0.8])
//             // ->format('json')
//             ->stream(false)
//             ->ask();

//         return $response;
//     }
// }


namespace App\AI;

use Cloudstudio\Ollama\Facades\Ollama;

class Ochat
{
    public function send(string $message)
    {
        $response = Ollama::model('llama3.2')
            ->prompt($message)
            ->options(['temperature' => 0.8])
            ->stream(false)
            ->ask();

        // Nếu $response là mảng, lấy giá trị của key 'response'
        if (is_array($response) && isset($response['response'])) {
            return $response['response'];
        }

        return 'Lỗi: Không thể lấy câu trả lời từ chatbot.';
    }
}
