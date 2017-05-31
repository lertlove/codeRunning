<?php
 
require_once __DIR__ . '/vendor/autoload.php';
require_once 'parser.php';
	$token = "EAAOucdHqidYBAAlEseadLwq7K2OB3AM452ZBBYIzZBvy667ZAeoBdylLRCt0HUhV3CRLGrTKvqnMZAmiwaVJhWZBZChamfyJy8pycgrFAd2TcTqic9RavMEAl6LQQtmIUsdnSbWtcI9N9c2XeDL64oZCLroPdyqYwOdsU61wt2PIwZDZD";
	// $token = "EAAOucdHqidYBAJJDkonT9rZB0n2UM8SjL5NVgjSE5XAZB5Kl3vZCHAXBgpBO3mreqXhwJEfErng55uXZBZCxlVda2k1L3s1LLM5QAtMvBReENR4ZBMkV5VUb446tij2szJIM4h4PSf8MLIvADny3AsTU9R3VtBEX00lnD1Lp2ZCZAQZDZD";
	// $token = "EAAOucdHqidYBAGKX1g2IpDjg1GiXZBuJyjzOAYCh97CHyuiLKD0kxWG9nVarIOiVeRZArspwbkwQ2mzjGLJCethyPvTuYIVmc2UXo7AyElrk8ZC9z0aYJugE8r1CpzlnBRJIMHMEemAeUjkD6QLjx0kjuOFNyolZAPjcxcrBDwZDZD";
	$verify_token = "mwit_token";
	$hub_verify_token = null;
	if(isset($_REQUEST['hub_challenge'])) {
	    $challenge = $_REQUEST['hub_challenge'];
	    $hub_verify_token = $_REQUEST['hub_verify_token'];
	}
	if ($hub_verify_token === $verify_token) {
	    echo $challenge;
	    exit;
	}

	file_put_contents("message.txt",file_get_contents("php://input"));
	$fb_message = file_get_contents("message.txt");
	$fb_message = json_decode($fb_message);
	$rec_id = $fb_message->entry[0]->messaging[0]->sender->id; //Sender's ID
	$rec_msg= $fb_message->entry[0]->messaging[0]->message->text; //Sender's Message

	if(!empty($rec_msg) && shouldReplyMessage($rec_msg)){

		$response_message = $rec_msg;
		// $response_message = parseMessage($rec_msg, "");
		if (!empty($response_message)) {
			$data_to_send = array(
			'recipient'=> array('id'=>"$rec_id"), //ID to reply
			'message' => array('text'=>$response_message) //Message to reply
			);

			$headers = [
				'Content-Type: application/json',
			];

			$process = curl_init("https://graph.facebook.com/v2.6/me/messages?access_token=$token");
			curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($process, CURLOPT_HEADER, false);
			curl_setopt($process, CURLOPT_TIMEOUT, 30);
			curl_setopt($process, CURLOPT_POST, 1);
			curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($data_to_send));
			curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
			$return = curl_exec($process);
			curl_close($process);
			return json_decode($return, true);
		}
	}
	 
?>