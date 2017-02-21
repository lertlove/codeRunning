<?php
 
$token = "EAAOucdHqidYBAJFtPBQRm83j72ijmmqKZAy052kHMOp8yf9ffdK6ydHs3Tw7SZCu45ZCRZCmcOGC5LWaZB8bFCQcQKS0d9WN7YZAqseOyM9KLOktEmOaY9snl2NHGEQi0Oiia45GHZC7QMy8M5B2ntPJZAA2wcOl7ZCA9dDAbeab90wZDZD";
 
file_put_contents("message.txt",file_get_contents("php://input"));
$fb_message = file_get_contents("message.txt");
$fb_message = json_decode($fb_message);
$rec_id = $fb_message->entry[0]->messaging[0]->sender->id; //Sender's ID
$rec_msg= $fb_message->entry[0]->messaging[0]->message->text; //Sender's Message
$data_to_send = array(
'recipient'=> array('id'=>"$rec_id"), //ID to reply
'message' => array('text'=>"Hi Mwit3. I am Test Bot") //Message to reply
);

$headers = [
	'Content-Type: application/json',
];

$process = curl_init("https://graph.facebook.com/v2.6/me/messages?access_token=$token");
curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
curl_setopt($process, CURLOPT_HEADER, false);
curl_setopt($process, CURLOPT_TIMEOUT, 30);
curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
$return = curl_exec($process);
curl_close($process);
return json_decode($return, true);
 
// header("HTTP/1.1 200 OK");

// $options_header = array ( //Necessary Headers
// 'http' => array(
// 'method' => 'POST',
// 'content' => json_encode($data_to_send),
// 'header' => "Content-Type: application/json\r\n"
// )
// );
// $context = stream_context_create($options_header);
// file_get_contents("https://graph.facebook.com/v2.6/me/messages?access_token=$token",false,$context);


 // /**
 //     * Request to API
 //     *
 //     * @access public
 //     * @param string $url
 //     * @param array  $data
 //     * @param string $type Type of request (GET|POST|DELETE)
 //     * @return array
 //     */
 //    function call($url, $data, $type = self::TYPE_POST)
 //    {
 //        $data['access_token'] = $this->token;
 //        $headers = [
 //            'Content-Type: application/json',
 //        ];
 //        if ($type == self::TYPE_GET) {
 //            $url .= '?'.http_build_query($data);
 //        }
 //        $process = curl_init($this->apiUrl.$url);
 //        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
 //        curl_setopt($process, CURLOPT_HEADER, false);
 //        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        
 //        if($type == self::TYPE_POST || $type == self::TYPE_DELETE) {
 //            curl_setopt($process, CURLOPT_POST, 1);
 //            curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($data));
 //        }
 //        if ($type == self::TYPE_DELETE) {
 //            curl_setopt($process, CURLOPT_CUSTOMREQUEST, "DELETE");
 //        }
 //        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
 //        $return = curl_exec($process);
 //        curl_close($process);
 //        return json_decode($return, true);
 //    }
?>