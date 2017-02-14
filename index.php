<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'parser.php';

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

define('CHANNEL_ACCESS_TOKEN', '15o60jaZ2VS5zxNCn/YHfie4/JZBYA9GbpnbmFCiaQkt91rHH1/iD6s6Y7g4h2KbzAcuDCj8cNd3JeNuAVigoEwZ/vhfwQ4udINHfI7p1iC+n1e+/lJEWA59NYGE8YGPeB4TiEq12Oz3SXtbpanm8QdB04t89/1O/w1cDnyilFU=');
define('CHANNEL_SECRET', '60f172d664490d49d7ff28b802949468');

$httpClient = new CurlHTTPClient(CHANNEL_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, ['channelSecret' => CHANNEL_SECRET]);

$content = file_get_contents('php://input');
$events = json_decode($content, true);

if (!empty($events['events'])) {
	foreach ($events['events'] as $event) {
		switch ($event['type']) {
			case 'message':
				$message = $event['message'];
				switch ($message['type']) {
					case 'text':
						$response_message = parseMessage($message['text'], $event['source']['userId']);
						break;
					case 'image':
						$contentId = $message['id'];
						$image = $bot->getMessageContent($contentId)->getRawBody();
						$response_message = parseImage($image, $event['source']['userId']);
						break;
					default:
						error_log("Unsupported message type: " . $message['type']);
						break;
				}

				if (!empty($response_message)) {
					$textMessageBuilder = new TextMessageBuilder($response_message);
					$response = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
					if ($response->isSucceeded()) {
						error_log('Success');

						return;
					}

					error_log('Fail ' . $response->getRawBody());
					break;
				}
			default:
				error_log("Unsupported event type: " . $event['type']);
				break;
		}
	}
}