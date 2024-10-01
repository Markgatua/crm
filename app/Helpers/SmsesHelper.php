<?php


namespace App\Helpers;
use  Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
// use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;



class SmsesHelper
{

    public static function sms($phone,$subject)
    {
        $endpoint = env('SMS_ENDPOINT');   
        $usrname = env('SMS_CLIENTID');
        $auth_token = env("SMS_APIKEY");
        $headers = array();
        $headers=[
          'apikey'=>$auth_token,
          'Content-Type'=>'application/x-www-form-urlencoded',
          'accept'=>'application/json'
        ];
        $client = new GuzzleClient([
          'headers'=>$headers
        ]);
        $response = $client->request('POST', $endpoint, [
            'multipart' => [
                [
                    'name'     => 'username',
                    'contents' => $usrname
                ],
                [
                    'name'     => 'to',
                    'contents' => $phone
                ],
                [
                    'name'     => 'message',
                    'contents' => $subject
                ]
                ]]);
    }

    /// Welcome sms after successful registration and phone verification

    /// Notify users on password changed
}