<?php

namespace App\Jobs;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SendResetPasswordSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mobileNumber;
    /**
     * @var string
     */
    private $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
        $this->message = strtolower(Str::random(64));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = (new Client)->request('POST', Config::get('custom_config.sms_url'), [
            'headers' => [
                'apikey' => Config::get('custom_config.sms_token')
            ],
            'json' => [
                'message' =>  route('password.reset', ['token' => $this->message]),
                'sender' => Config::get('custom_config.sms_sender'),
                'Receptor' => $this->mobileNumber,
            ]
        ]);
        if (!empty($response)) {
            if (!empty($response->getBody())) {
                if ((json_decode($response->getBody()->getContents()))->result == 'success') {
                    DB::table('password_resets')->insert([
                        'mobile' => $this->mobileNumber,
                        'token' => $this->message,
                        'created_at' => Carbon::now()
                    ]);
                }
            }
        }
    }
}
