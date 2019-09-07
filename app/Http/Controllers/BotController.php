<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;
use App\Chat;
use App\Order;
use App\Recipient;
use App\Apparel;
class BotController extends Controller
{
    public function bot(Request $request)
    {
        static $order = array();
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


        if(preg_match('/(01)|\+880|001|1(.*?)/', $messageText)){
            
            $order_id = Order::where('recipient_id', $senderId)->orderBy('updated_at','desc')->pluck('id')->first();
            $updateOrder = Order::find($order_id);
            $updateOrder->phone = $messageText;
            $updateOrder->status = 'done';
            $updateOrder->save();

            $this->typingAnimation($senderId,$accessToken);

            $message = "Thanks. Our agent will contact with you soon. Make sure you recieve our call. ";

            $this->sendTextMessage($message, $senderId, $accessToken);
            exit();
        }

        if($messageText !== ''){
            $answer = Chat::where('message_like','LIKE',"%$messageText%")->pluck('reply_with')->first();

            if(empty($answer)){

                $this->typingAnimation($senderId,$accessToken);
                $answer = 'I dont understand what you have just said. :\'( Can I tell you a joke ? :D ';

                $response_json = '{
                    "recipient": {
                        "id": "'.$senderId.'"
                    },
                    "message": {
                        "attachment":{
                            "type":"template",
                            "payload":{
                                "template_type":"button",
                                "text":"'.$answer.'",
                                "buttons": [
                                    {
                                        "type": "postback",
                                        "title": "Hell yeah !",
                                        "payload": "telljoke"
                                    },
                                    {
                                        "type": "postback",
                                        "title": "No show me some tshirts now",
                                        "payload": "tshirtshow"
                                    },


                                ]
                            }
                        }
                    }
                }';
            }

            else{
                $response_json = '{
                    "recipient": {
                        "id": "'.$senderId.'"
                    },
                    "message": {
                        "text": "'.$answer.'"
                    }
                }';

            }

                $this->typingAnimation($senderId,$accessToken);
                $this->sendMessage($accessToken, $response_json);

        }

        if($postback == 'showgraphics'){

            $response_json = '{
                "recipient": {
                    "id": "'.$senderId.'"
                },
                "message": {
                    "attachment":{
                        "type":"template",
                        "payload":{
                            "template_type":"media",
                            "elements": [
                                {
                                   "media_type": "IMAGE",
                                   "url": "https://www.facebook.com/Bees152/photos/a.363708130854082/391726388052256/?type=3&theater",
                                   "buttons": [
                                    {
                                       "type": "web_url",
                                       "url": "www.fahim152.com",
                                       "title": "View Website",
                                    }
                                 ]
                                }

                            ]

                        }
                    }
                }
            }';
            $this->sendMessage($accessToken, $response_json);
            exit();
        }


        if($postback == 'telljoke'){
            $joke = json_decode(file_get_contents('http://api.icndb.com/jokes/random'), true);
            $joke_get = $joke['value']['joke'];
            $this->typingAnimation($senderId,$accessToken);
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
                                    "text":"Wanna hear another joke ?",
                                    "buttons": [
                                        {
                                            "type": "postback",
                                            "title": "Hell yeah !",
                                            "payload": "telljoke"
                                        },
                                        {
                                            "type": "postback",
                                            "title": "Show me some tshirts now !",
                                            "payload": "tshirtshow"
                                        },
                                        {
                                            "type":"web_url",
                                            "url":"http://www.fahim152.com",
                                            "title":"Show website"
                                        },

                                    ]
                                }
                            }
                        }
                    }';

                        $this->sendMessage($accessToken, $response_json);
        }

        if($postback == 'tshirtshow'){

            $this->typingAnimation($senderId,$accessToken);
            $message = 'Checkout our new collections.';
            $this->sendTextMessage($message, $senderId, $accessToken);
            $response_json = '{
                "recipient": {
                    "id": "'.$senderId.'"
                },
                "message": {
                    "attachment":{
                        "type":"template",
                        "payload":{
                          "template_type":"generic",
                          "elements":[
                             {
                              "title":"Cobra Tshirt",
                              "image_url":"https://i.ibb.co/cbZWB2g/t-shirt-sublimation-clothing-polo-shirt-t-shirt.jpg",
                              "subtitle":"Only at BDT 450",
                              "default_action": {
                                "type": "web_url",
                                "url": "https://www.facebook.com/Bees152/photos/pcb.474862716405289/474862636405297/?type=3&theater",

                                "webview_height_ratio": "tall",
                              },
                              "buttons":[
                               {
                                  "type":"postback",
                                  "title":"Order Now",
                                  "payload":"tshirt1_order"
                                }
                              ]
                            },
                            {
                                "title":"Alien Tshirt",
                                "image_url":"https://i.ibb.co/KcPyg4R/kisspng-t-shirt-polo-shirt-team-sport-collar-arnold-palmer-stribe-polo-5bae80cc0ca141-7292460915381628920517.jpg",
                                "subtitle":"Only at BDT 300 ",
                                "default_action": {
                                  "type": "web_url",
                                  "url": "https://www.facebook.com/Bees152/photos/pcb.474862716405289/474862596405301/?type=3&theater",
                                  "webview_height_ratio": "tall",
                                },
                                "buttons":[
                                  {
                                    "type":"postback",
                                    "title":"Order Now",
                                    "payload":"tshirt2_order"
                                  }
                                ]
                              },
                              {
                                "title":"Supra Tshirt",
                                "image_url":"https://i.ibb.co/Fn66p7M/imgbin-sports-fan-jersey-t-shirt-polo-shirt-collar-sleeve-tshirt-pattern-h-Jnpn707d-FCv-Znd-S1-Bcz-Wf-Wwb.jpg",
                                "subtitle":"Only at BDT 350",
                                "default_action": {
                                  "type": "web_url",
                                  "url": "https://www.facebook.com/Bees152/photos/pcb.474862716405289/474862603071967/?type=3&theater",
                                  "webview_height_ratio": "tall",
                                },
                                "buttons":[
                                  {
                                    "type":"postback",
                                    "title":"Order Now",
                                    "payload":"tshirt3_order"
                                  }
                                ]
                              },
                          ]
                        }
                      }
                }
            }';
            $this->sendMessage($accessToken, $response_json);
            exit();
        }

        if(preg_match('/(.*?)_order/', $postback)){

            $splitOrderDetails = explode("_",$postback);
            $apparel_id = Apparel::whereModel($splitOrderDetails[0])->pluck('id')->first();

            $order = new Order;
            $order->recipient_id =  $senderId;
            $order->status =  'phone_given';
            $order->apparel_id =  $apparel_id ;

            $order->save();
            $this->typingAnimation($senderId,$accessToken);
            $message = "Please Insert Your phone number. We will get back to you as soon as possible";
                    $response_json = '{
                        "recipient": {
                            "id": "'.$senderId.'"
                        },
                        "message": {
                            "text": "'.$message.'"
                        }
                    }';

                    $this->sendMessage($accessToken, $response_json);
        }


            if(isset($messagingArray['postback'])){
                if($messagingArray['postback']['payload'] == 'first_hand_shake'){

                    $this->typingAnimation($senderId,$accessToken);
                    $message = "Hello Dear, Welcome to our page. why dont you just check our services or the website";
                    $this->sendTextMessage($message, $senderId, $accessToken);


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
                                            "type": "postback",
                                            "title": "Show me some tshirt Now !",
                                            "payload": "tshirtshow"
                                        },
                                        {
                                            "type":"web_url",
                                            "url":"http://www.fahim152.com",
                                            "title":"Show website"
                                        }

                                    ]
                                }
                            }
                        }
                     }';
                     $this->sendMessage($accessToken, $joke_json);
                     exit();
                }


            }



    }

    public function typingAnimation($senderId, $accessToken){
        $typing_animation ='{
            "recipient":{
              "id":"'.$senderId.'"
            },
            "sender_action":"typing_on"
          }';
          $this->sendMessage($accessToken, $typing_animation);
    }

    public function sendTextMessage($message, $senderId, $accessToken){

        $response_json = '{
            "recipient": {
                "id": "'.$senderId.'"
            },
            "message": {
                "text": "'.$message.'"
            }
        }';
        $this->sendMessage($accessToken, $response_json);
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

