<?php

namespace App\Http\Components;

class Curl {

    public static function call($url = "", $method = "GET", $header = [], $post_fields = "") {

        $curl = curl_init();

        $data = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $header
        ];
        if($method = "POST") {
            $data[CURLOPT_POSTFIELDS] = $post_fields;
        }

        curl_setopt_array($curl, $data);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $data['error'] = "cURL Error #:" . $err;
        } else {
            $data = json_decode($response, true);
            $data['success'] = true;
        }
        return $data;
    }

}
