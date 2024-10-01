<?php


namespace App\Helpers;
use  Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
// use Illuminate\Http\Request;


class EmailsHelper
{

    public static function emailverificationmailone($email,$code)
    {
        View::make('emailverificationmail');

        $mail = Mail::send('emailverificationmail', ['code'=>$code], function($message) use($email){
            $message->to($email)->subject(env('APP_NAME').' Email Verification');
         });
    }

    public static function emailpasswordresetmail($email,$code)
    {
        View::make('passwordresetmail');

        $mail = Mail::send('passwordresetmail', ['code'=>$code], function($message) use($email){
            $message->to($email)->subject(env('APP_NAME').' Password Reset');
         });
    }

    // Welcome email after successful registration and email verification


    //Notify users when password is changed

}