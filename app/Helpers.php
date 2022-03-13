<?php

if(!function_exists('sendResponseToSlack')){
    function sendResponseToSlack($message){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://hooks.slack.com/services/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',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode([
                'blocks' => [
                    [
                        'type' => 'section',
                        'text' => [
                            'type' => 'mrkdwn',
                            'text' => $message
                        ]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    }
}

if(!function_exists('xmlEscape')){
    function xmlEscape($string) {
        return str_replace(['&', '<', '>', '\'', '"'], ['&amp;', '&lt;', '&gt;', '&apos;', '&quot;'], $string);
    }
}
