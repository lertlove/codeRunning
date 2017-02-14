<?php
require_once 'storage.php';

function parseMessage($message, $actor) {
	$messages = explode(',', $message);
	$responseMessage = "ว่าไงนะ?";

	if (count($messages) == 2) {
		$item = trim($messages[0]);
		$amount = (int) trim($messages[1]);

		addRecord($item, $amount, $actor);
		$responseMessage = sprintf("เพิ่มรายการ '%s' จำนวนเงิน %d บาท", $item, $amount);
	}

	return $responseMessage;
}

function parseImage($image, $actor) {
	$filename = addImage($image, $actor);

	return sprintf("เพิ่มรูปประกอบ %s", $filename);
}