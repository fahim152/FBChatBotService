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
        $messagingArray = $data['entry'][0]['messaging'][0];

        if($messageText !== ''){
            $answer = Chat::where('message_like','LIKE',"%$messageText%")->pluck('reply_with')->first();
      
            if(empty($answer)){
               
                $answer = 'I dont understand what you have just said';
            }
          
            $response_json = '{
                "recipient": {
                    "id": "'.$senderId.'"
                },
                "message": {
                    "text": "'.$answer.'"
                }
            }';

                $this->sendMessage($accessToken, $response_json);

        }
      

        if($postback == 'telljoke'){
            $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'), true);
            $joke_get = $joke['value']['joke'];
             
                $response_json = '{
                    "recipient": {
                        "id": "'.$senderId.'"
                    },
                    "message": {
                        "text": "'.$joke_get.'"
                    }
                }';

                    $this->sendMessage($accessToken, $response_json);

                    $response_json = '{
                        "recipient": {
                            "id": "'.$senderId.'"
                        },
                        "message": {
                            "attachment":{
                                "type":"template",
                                "payload":{
                                    "template_type":"button",
                                    "text":"Need another joke ?",
                                    "buttons": [
                                        {
                                            "type": "postback",
                                            "title": "Hell yeah !",
                                            "payload": "telljoke"
                                        },
                                        {
                                            "type":"web_url",
                                            "url":"http://www.thispersondoesnotexist.com",
                                            "title":"Show website"   
                                        }
                                       
                                    ]
                                }
                            }
                        }
                    }';
    
                        $this->sendMessage($accessToken, $response_json);
        }    
            if(isset($messagingArray['postback'])){
                if($messagingArray['postback']['payload'] == 'first_hand_shake'){
                 
                    $message = "Hello Dear, Welcome to our page. why dont you just check our services or the website";
                    $response_json = '{
                        "recipient": {
                            "id": "'.$senderId.'"
                        },
                        "message": {
                            "text": "'.$message.'"
                        }
                    }';

                    $this->sendMessage($accessToken, $response_json);

                    $joke_json = '{
                        "recipient":{
                            "id":"'.$senderId.'"
                        },
                        "message": {
                            "attachment":{
                                "type":"template",
                                "payload":{
                                    "template_type":"button",
                                    "text":"You can View our website",
                                    "buttons": [
                                        {
                                            "type": "postback",
                                            "title": "Tell me a joke",
                                            "payload": "telljoke"
                                        },
                                        {
                                            "type":"web_url",
                                            "url":"http://www.thispersondoesnotexist.com",
                                            "title":"Show website"   
                                        }
                                       
                                    ]
                                }
                            }
                        }
                     }';
                     $this->sendMessage($accessToken, $joke_json);
                }
                
            }
       
           
        
    }
        
    public function sendMessage($accessToken, $response_json){
       
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response_json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

