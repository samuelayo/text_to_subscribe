<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use App\User;
use App\subscriptions;

class SubscriptionsController extends Controller
{
    //
    private $client;
    public function __construct()
    {
        $account_sid = 'XXX_ACCOUNT_SID';
        $auth_token = 'XXX_AUTH_TOKEN';
        $this->client = new Client($account_sid, $auth_token);
    }
    public function index(Request $request){
        $from = $request->input('From');
        $to = $request->input('To');
        $message = strtolower($request->input('Message'));
        // check if number is registered
        $isRegistered = User::where('phone', $from)->first();
        if(!$isRegistered){
            return $this->askToRegister($from, $to, $request->getSchemeAndHttpHost());
        }
        switch ($message) {
            case 'subscribe':
                # code...
                return $this->subscribe($from, $to, $isRegistered);
                break;
            case 'cancel':
            return $this->unSubscribe($from, $to, $isRegistered);
                # code...
                break;
            default:
                return $this->showAccepted($from, $to,  $isRegistered);
                break;
        }
    }

    public function subscribe($userNumber, $myNumber, $userDetails){
        $subscribe = subscriptions::where('user_id', $userDetails->id)->first();
        $message = '';
        if($subscribe){
            if(!$subscribe->status == true){
                $message .= "You are already subscribed to the service. Send cancel if you want to unsubscribe";
            }else{
                $message .= "You have been sucessfully subscribed. Send cancel if you want to unsubscribe";
                $subscribe->status = 1;
                $subscribe->save();
            }
        }else{
            $message .= "You have been sucessfully subscribed. Send cancel if you want to unsubscribe";
            $flight = new subscriptions;
            $flight->user_id = $userDetails->id;
            $flight->status = true;
            $flight->save();
        }
        $body = "Hi $userDetails->name, $message";
        return $this->sendSms($userNumber, $myNumber, $body);
    }

    public function unSubscribe($userNumber, $myNumber, $userDetails){
        $subscribe = subscriptions::where('user_id', $userDetails->id)->first();
        $message = '';
        if(!$subscribe){
            $message .= "You are not yet subscribed to the service, Please send 'subscribe' to subscribe";
        }else{
            if($subscribe->status == false){
                $message .= "You are already unsubscribed to the service. Send subscribe if you want to subscribe";
            }else{
                $message .= "You have been sucessfully unsubscribed from the service. Send subscribe to rejoin";
                $subscribe->status = false;
                $subscribe->save();
            }
        }
        $body = "Hi $userDetails->name, $message";       
        return $this->sendSms($userNumber, $myNumber, $body);
    }

    public function showAccepted($userNumber, $myNumber, $userDetails){
        $body = "Hi $userDetails->name, \r\n you have sent an incorrect keyword. \r\n Please try any of this: \r\n 1.) subscribe \r\n 2.) cancel";
        return $this->sendSms($userNumber, $myNumber, $body);
    }
    public function askToRegister($userNumber, $myNumber, $url){
        $body = 'Hi there, you are not a registered number yet. Please logon to '.$url.' to register';
        return $this->sendSms($userNumber, $myNumber, $body);
    }

    public function sendSms($userNumber, $myNumber, $body){
        return $this->client->messages->create(
            // Where to send a text message (your cell phone?)
            $userNumber,
            array(
                'from' => $myNumber,
                'body' => $body
            )
        );
    }
}
