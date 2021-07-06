<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class TwilioSms
{
    /**
     * @param $notifiable
     * @param Notification $notification
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toTwilioSms($notifiable);

        $accountSid = config('constants.twilio_account_id');
        $authToken = config('constants.twillio_auth_token');
        $twilioNumber = config('constants.twillio_number');

        $client = new Client($accountSid, $authToken);

        try {
            $client->messages->create(
                $message->phone,
                [
                    "body" => $message->sms_code,
                    "from" => $twilioNumber                    
                ]
            );
        } catch (TwilioException $e) {
            Log::error(
                'Could not send SMS notification.' .
                ' Twilio replied with: ' . $e
            );
        }        
    }
}