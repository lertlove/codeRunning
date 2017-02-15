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
	$range = 'student 1-300-1!A:Z';
	$response = $service->spreadsheets_values->get($spreadsheetId, $range);
	$rows = $response->getValues();
	$message = trim($message);
	
	$results = [];

	foreach ($rows as $row) {
		
		if (strpos($row[1],$message) !== false) {
		    $results[] = "code:".$row[0]." , ".$row[1]." , ".$row[3]." , tel:".$row[5];
		}

		if(count($results) > 5){
			break;
		}
	}

	if(count($results)<1){
		$results = "ไม่พบข้อมูล โปรดเช็คตัวสะกดแล้วลองใหม่อีกครั้งนะคะ";
	} else{
		$results = implode("/----------------------/", $results);	
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