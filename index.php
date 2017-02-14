<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'parser.php';

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

define('CHANNEL_ACCESS_TOKEN', 'fJwCjyz8T+/A4AEvp61DKeb6IWnqvth4fRDhtrbwHUadLHlqYka9e2Q36ScctXp/T/ZNTgNIPB/HLHu0YhCHxP93rCUr2aBKKT4NgFRdiw8eKuGUCAbtBk79mYRGD9C7FK62OVKzfIU7pkxlsrpq2gdB04t89/1O/w1cDnyilFU=');
define('CHANNEL_SECRET', '63dd756d756ce5b696d966a80056f423');

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