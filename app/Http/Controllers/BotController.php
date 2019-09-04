<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use App\Chat;
class BotController extends Controller
{
    public function bot(Request $request)
    {

        $data = $request->all();
        //fetching access token from db based on sent app token.
        //$accessToken = Setting::where('fb_app_name', $request['hub_verify_token'])->pluck('token')->first();

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

        $senderId =  $data['entry'][0]['messaging'][0]['sender']['id'];
        $messageText =  isset($data['entry'][0]['messaging'][0]['message']['text']) ? $data['entry'][0]['messaging'][0]['message']['text'] : '';
        $postback = isset($data['entry'][0]['messaging'][0]['postback']['payload']) ? $data['entry'][0]['messaging'][0]['postback']['payload']: '';
        $attach = false;
        $answer = '';
        if($messageText !== ""){

            // if(preg_match('/(send|tell|text)(.*?)joke/', $messageText)){
            //     $resource = json_decode(file_get_contents('http://api.icndb.com/jokes/random'), true);
            //     $answer = $resource['value']['joke'];

            // }

            $answer = Chat::where('message_like','LIKE',"%$messageText%")->pluck('reply_with')->first();
            if($answer == null || $answer == ""){
                $answer="I'm afraid :( I can't understand what you have just said. Do you want me to send an image? reply with yes";

            }
            if($messageText == "yes"){
                $attach = true;
            }elseif($messageText == "no"){
                $dd =  $this->sendMessagePostBack($accessToken, $senderId);

            }
        }

        if($attach){
            $response = [
                'recipient' => [ 'id' => $senderId ],
                 'message' => [ 'attachment' => [ 'type' => 'image', 'payload' => [ 'url' => 'https://www.google.com.bd/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png'] ]],
            ];
        }else{
            $response = [
                'recipient' => [ 'id' => $senderId ],
                "message"   => [ "text" => $answer ],
            ];


        }

        $this->sendMessage($accessToken, $response);
        $this->sendMessagePostBack($accessToken, $senderId);

    }


    public function sendMessage($accessToken,$response){
        $ch = curl_init('https://graph.facebook.com/v4.0/me/messages?access_token='.$accessToken);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    public function sendMessagePostBack($accessToken,$senderId){
        $response_json = '{
            "recipient":{
                "id":"'.addslashes($senderId).'"
            },
            "message": {
                "attachment":{
                    "type":"template",
                    "payload":{
                        "template_type":"button",
                        "text":"You can View our website";
                        "buttons": [
                            {
                                "type":"web_url",
                                "utl":"www.daraz.com",
                                "title":"Show website"
                            },
                            {
                                "type":"postback",
                                "title":"Start Chatting",
                                "payload":"USER_DEFINED_PAYLOAD"
                            }
                        ]
                    }
                }
            }
         }';
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
// greeting messge sending along with button template, pending work.
    //     $response_greeting = [
    //         'recipient' => [ 'id' => $senderId ],
    //         'message' => [
    //                     'attachment' => ['type' => 'template',
    //                                         'payload' => [
    //                                         'template_type' => 'generic',
    //                                         'elements' => [
    //                                             'title' => "kitten",
    //                                             'subtitle' => 'cute kitten pic',
    //                                             'image_url' => 'https://placekitten.com/g/200/200',
    //                                             'button' => [
    //                                                 'type' => 'web_url',
    //                                                 "url"   => 'https://placekitten.com/g/200/200',
    //                                                 "title" => "Show kitten",
    //                                             ],
    //                                                 'type' => 'postback',
    //                                                 'title' => 'I like this',
    //                                                 "payload" => "User " + $senderId + " likes kitten " + 'https://placekitten.com/g/200/200',

    //                                                 ]
    //                                         ]
    //                                     ]

    //                         ],


    //                      ];



    // }


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
