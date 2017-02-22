<?php
define('APPLICATION_NAME', 'MWITS Science Runners6-2-60');
define('CLIENT_SECRET_PATH', 'client_secret.json');
define('SCOPES', implode(' ', array(
	Google_Service_Sheets::SPREADSHEETS
)));
define('SPREADSHEET_ID', '1Xq9E6VVMRj3e9IaFUM6KHTYm5GhCiqCEMm5CToyor4k');

function getService() {
	$client = new Google_Client();
	$client->setApplicationName(APPLICATION_NAME);
	$client->setScopes(SCOPES);
	$client->setAuthConfig(CLIENT_SECRET_PATH);
	$service = new Google_Service_Sheets($client);

	return $service;
}

// function findRecord($message, $amount, $actor) {
function findRecord($message) {
	$service = getService();
	$spreadsheetId = SPREADSHEET_ID;
	$range = 'Total for Search Engine!A:Z';
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$rows = $response->getValues();
	$message = trim($message);

	$type = "text";
	if(preg_match("/^[\+0-9\-\(\)\s]*$/", $message)){
		$type = "tel";
		$message = preg_replace('/\D/', '', $message);
	}

	$results = [];

	foreach ($rows as $row) {

		if ((isset($row[5]) && preg_replace('/\D/', '', $row[5]) == $message)) {
		    $results[] = "source:".$row[0]."\n"."code:".$row[1]."\n".$row[2]."\n".$row[4]."\nsizeเสื้อ:".$row[5];
		} else if ((isset($row[1]) && strpos($row[1],$message) !== false)) {
		    $results[] = "source:".$row[0]."\n"."code:".$row[1]."\n".$row[2]."\n".$row[4]."\nsizeเสื้อ:".$row[5];
		}

		if(count($results) > 5){
			break;
		}
		
	}

	if(count($results)<1){
		$results = "ไม่พบข้อมูล โปรดเช็คตัวสะกดแล้วลองใหม่อีกครั้งนะคะ";
	} else{
		$results = implode("\n/----------------------/\n", $results);	
	}
	

	return $results;


	// $rows = Sheets::sheet('สำหรับแปล')->get();
	
	// $time = date('Y-m-d H:i:s');

	// $values = [
	// 	[$time, $actor, $message, $amount]
	// ];

	// $body = new Google_Service_Sheets_ValueRange(array(
	// 	'values' => $values
	// ));

	// $params = array(
	// 	'valueInputOption' => 'USER_ENTERED'
	// );

	// $service->spreadsheets_values->append(SPREADSHEET_ID, 'Main!A1', $body, $params);
}

function addImage($image, $actor) {
	$tmpfilePath = tempnam($_SERVER['DOCUMENT_ROOT'] . '/image', '');
	unlink($tmpfilePath);
	$filePath = $tmpfilePath . '.jpg';
	$filename = basename($filePath);

	$fh = fopen($filePath, 'x');
	fwrite($fh, $image);
	fclose($fh);

	return $filename;
}