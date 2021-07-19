<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $mobileNumber;
    protected $message;


    public function __construct($mobileNumber, $message)
    {
        $this->mobileNumber = $mobileNumber;
        $this->message = $message;
    }


    public function handle()
    {
        (new Client)->request('POST', Config::get('custom_config.sms_url'), [
            'headers' => [
                'apikey' => Config::get('custom_config.sms_token')
            ],
            'json' => [
                'message' => $this->message,
                'sender' => Config::get('custom_config.sms_sender'),
                'Receptor' => $this->mobileNumber,
            ]
        ]);
    }
}
