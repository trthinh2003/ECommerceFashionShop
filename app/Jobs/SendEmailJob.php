<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        try {
            // Log::info("Bắt đầu gửi email đến: " . $this->data['email']);
            Mail::to('raucuquasachnhom@gmail.com')->send(new ContactMail($this->data));
            // Log::info("Gửi email thành công đến: raucuquasachnhom@gmail.com");
            // Log::info("Dữ liệu gửi mail: " . json_encode($this->data));
        } catch (\Exception $e) {
            Log::error("Gửi email thất bại: " . $e->getMessage());
        }
    }


    // public function handle()
    // {
    //     try {
         
    //         $data = $this->data;
    //         Log::info("Bắt đầu gửi email đến: " . $data);
    //         // Truyền biến $this->data vào closure bằng từ khóa use
    //         Mail::raw("Tên người gửi: {$data['name']}\nĐịa chỉ Email: {$data['email']}\nNội dung: {$data['message']}", function ($message) use ($data) {
    //             $message->from('raucuquasachnhom@gmail.com', $data['name'])
    //                     ->to('raucuquasachnhom@gmail.com', 'TST Fashion Shop')
    //                     ->subject('Thông tin liên hệ từ ' . $data['name']);
    //         });
    
    //         Log::info("Gửi email thành công đến: " . $data['email']);
    //     } catch (\Exception $e) {
    //         Log::error("Gửi email thất bại: " . $e->getMessage());
    //     }
    // }
    





}
