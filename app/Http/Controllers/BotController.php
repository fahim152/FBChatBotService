<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
class BotController extends Controller
{
    public function bot(Request $request)
    {

        $data = $request->all();
        //fetching access token from db based on sent app token.
     //   $accessToken = Setting::where('fb_app_name', $request['hub_verify_token'])->pluck('token')->first();

        /**
         * here we can make this dynamic by simple fetching app names from db. store it in array and find name via in_array() function.
         *  I'm keeping it simple for now
         * */
        $hubVerifyToken = 'freelancebees';

        $accessToken ="EAAjVJKXVuZC8BAGXzdgJfoXZCH4wtlSZAcZAzZBMDeX9oVxsxR8yxYWOZAo2B3ggH1JmwySOrJZAFKitNzpHpBqGtkTbaERMsoRwAQZCrra93kJInWfQI5aGOXBBPK7RgdTwZAjMxsid7wYuShBCdWAeqcIVYT5SgGPMwdjRSikC6KYGNSLqTlLnjAKZAacHdV02YZD";

        if ($request['hub_verify_token'] === $hubVerifyToken) {
          echo $request['hub_challenge'];
          exit;
        }

        $senderId = $data['entry'][0]['messaging'][0]['sender']['id'];
        $messageText =  $data['entry'][0]['messaging'][0]['message']['text'];

        if($messageText== "hi"){
            $answer="hello Sir, Do you need assistance? Reply yes or no ";
        }elseif($messageText == "yes"){
            $answer="Great you have it then.";
        }elseif($messageText == "no"){
            $answer="Okay sir. See you again";
        }else{
            $answer="I dont understand what you have said, Do you need assistance? Please reply yes or no ";
        }

        $response = [
            'recipient' => [ 'id' => $senderId ],
            'message' => [ 'text' => $answer ],
        ];

        $ch = curl_init('https://graph.facebook.com/v4.0/me/messages?access_token='.$accessToken);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $result = curl_exec($ch);

        curl_close($ch);


    }
}

// Another approach

// $data = $request->all();
//
//         $id            = $data["entry"][0]["messaging"][0]["sender"]["id"];
//         $senderMessage = $data["entry"][0]["messaging"][0]['message'];


//         if (!empty($senderMessage)) {
//             $this->sendTextMessage($id, "Hi buddy");
//         }
//     }
//     private function sendTextMessage($recipientId, $messageText)
//     {
//         $messageData = [
//             "recipient" => [
//                 "id" => $recipientId,
//             ],
//             "message"   => [
//                 "text" => $messageText,
//             ],
//         ];
//         $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . env("PAGE_ACCESS_TOKEN"));

//         curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));
//         curl_exec($ch);
//         curl_close($ch);
//     }
