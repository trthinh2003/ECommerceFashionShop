<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AI\Ochat;
use function Laravel\Prompts\{outro, text, info, spin};


class OchatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ochat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Chat with Ollama.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ochat = new Ochat();

        // $question = "what is 2 + 2, return the results in JSON format";
        $question = text(label:'What is your question for the LLama?');

        $response = spin(fn() => $ochat->send($question), 'Sending request...');

        // dd($response);

        info($response['response']);

        while($question = text('Would you like to keep going?')){
            $response = spin(fn () => $ochat->send($question), 'Sending request...');
            info($response['response']);
        }

        outro('Thank You, Buh Bye!');

    }
}
