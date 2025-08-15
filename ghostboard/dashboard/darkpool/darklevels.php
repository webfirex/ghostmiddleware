<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set headers to allow cross-origin requests if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Initialize cURL session
$ch = curl_init();

curl_setopt_array($ch, [
  CURLOPT_URL => "https://v3.quantdata.us/api/equities/dark-pool/levels/21894376-23ce-49d5-8cbc-584348af9e95",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Accept: */*",
    "Authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVhdGVkVGltZSI6MTcyOTYzNjMwNzk0MiwidXNlcklkIjoiMzI2Zjk1MWMtNmYxYS00YTFhLWFhYjgtNDA3MGYwOTNhMzg2IiwiaXNzIjoiUXVhbnQgRGF0YSJ9.eWAFAoUeA-epoaqyCeaClmCyzlVfFiTeHPuInDJoetg",
    "Cookie: _lr_tabs_-alsmmd%2Fquant-data-llc={%22recordingID%22:%226-0194759b-b1a8-71da-b8c2-c1656f77db0e%22%2C%22sessionID%22:0%2C%22lastActivity%22:1737139930619%2C%22hasActivity%22:true}; _lr_hb_-alsmmd%2Fquant-data-llc={%22heartbeat%22:1737139925420}; _lr_uf_-alsmmd=a58b5c04-3cb8-4452-a875-2c31fe30afd9; intercom-device-id-rxw83n6n=951348ee-31fd-4fbe-a434-4094bde828fc; intercom-session-rxw83n6n=cVYwd01CcWl0TVFpc3BDVHM4WjkzcDZtWkF4NHptUHRtTzNTbDZzTVFkeW5CbVJNTGEwcUY0eEdtNy9XcmVHYi0tbjhjbWg4MUxQVzdQVFpvZVB1a3Bpdz09--54f30c7c6578efe7c2683735bb30b666419c972d; _ga_YGJ6CZ21GK=GS1.2.1737122936.9.1.1737123085.0.0.0; _fbp=fb.1.1733874713739.829801824338315547; _ga=GA1.2.451680633.1733874714; _gid=GA1.2.1324405007.1736906048; intercom-id-rxw83n6n=82c7e264-3f6d-4f69-a4c7-c8b938884697; token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVhdGVkVGltZSI6MTcyOTYzNjMwNzk0MiwidXNlcklkIjoiMzI2Zjk1MWMtNmYxYS00YTFhLWFhYjgtNDA3MGYwOTNhMzg2IiwiaXNzIjoiUXVhbnQgRGF0YSJ9.eWAFAoUeA-epoaqyCeaClmCyzlVfFiTeHPuInDJoetg; intercom-device-id-rxw83n6n=8af57751-47e8-4a31-8bb4-a693af9795ad",
    "User-Agent: Thunder Client (https://www.thunderclient.com)"
  ],
]);

// Execute the request
$marketDataResponse = curl_exec($ch);
if (curl_errno($ch) || !$marketDataResponse) {
    die(json_encode(["error" => "Failed to fetch market data."]));
}

curl_close($ch);

$marketData = json_decode($marketDataResponse, true);
if (empty($marketData) || !is_array($marketData)) {
    die(json_encode(["error" => "Invalid market data response."]));
}

// Save the transformed data to JSON
$outputFile = 'levelsD.json';
if (file_put_contents($outputFile, json_encode($marketData, JSON_PRETTY_PRINT)) === false) {
    die(json_encode(["error" => "Failed to write to output file."]));
}

// Return success response
echo json_encode(["message" => "Data saved successfully to $outputFile"]);


// Initialize cURL session
$ch = curl_init();

curl_setopt_array($ch, [
  CURLOPT_URL => "https://v3.quantdata.us/api/equities/dark-pool/levels/e226bcaa-42ca-46c2-b477-33f8060802b5",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Accept: */*",
    "Authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVhdGVkVGltZSI6MTcyOTYzNjMwNzk0MiwidXNlcklkIjoiMzI2Zjk1MWMtNmYxYS00YTFhLWFhYjgtNDA3MGYwOTNhMzg2IiwiaXNzIjoiUXVhbnQgRGF0YSJ9.eWAFAoUeA-epoaqyCeaClmCyzlVfFiTeHPuInDJoetg",
    "Cookie: _lr_tabs_-alsmmd%2Fquant-data-llc={%22recordingID%22:%226-0194759b-b1a8-71da-b8c2-c1656f77db0e%22%2C%22sessionID%22:0%2C%22lastActivity%22:1737139930619%2C%22hasActivity%22:true}; _lr_hb_-alsmmd%2Fquant-data-llc={%22heartbeat%22:1737139925420}; _lr_uf_-alsmmd=a58b5c04-3cb8-4452-a875-2c31fe30afd9; intercom-device-id-rxw83n6n=951348ee-31fd-4fbe-a434-4094bde828fc; intercom-session-rxw83n6n=cVYwd01CcWl0TVFpc3BDVHM4WjkzcDZtWkF4NHptUHRtTzNTbDZzTVFkeW5CbVJNTGEwcUY0eEdtNy9XcmVHYi0tbjhjbWg4MUxQVzdQVFpvZVB1a3Bpdz09--54f30c7c6578efe7c2683735bb30b666419c972d; _ga_YGJ6CZ21GK=GS1.2.1737122936.9.1.1737123085.0.0.0; _fbp=fb.1.1733874713739.829801824338315547; _ga=GA1.2.451680633.1733874714; _gid=GA1.2.1324405007.1736906048; intercom-id-rxw83n6n=82c7e264-3f6d-4f69-a4c7-c8b938884697; token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVhdGVkVGltZSI6MTcyOTYzNjMwNzk0MiwidXNlcklkIjoiMzI2Zjk1MWMtNmYxYS00YTFhLWFhYjgtNDA3MGYwOTNhMzg2IiwiaXNzIjoiUXVhbnQgRGF0YSJ9.eWAFAoUeA-epoaqyCeaClmCyzlVfFiTeHPuInDJoetg; intercom-device-id-rxw83n6n=8af57751-47e8-4a31-8bb4-a693af9795ad",
    "User-Agent: Thunder Client (https://www.thunderclient.com)"
  ],
]);

// Execute the request
$marketDataResponse = curl_exec($ch);
if (curl_errno($ch) || !$marketDataResponse) {
    die(json_encode(["error" => "Failed to fetch market data."]));
}

curl_close($ch);

$marketData = json_decode($marketDataResponse, true);
if (empty($marketData) || !is_array($marketData)) {
    die(json_encode(["error" => "Invalid market data response."]));
}

// Save the transformed data to JSON
$outputFile = 'levelsM.json';
if (file_put_contents($outputFile, json_encode($marketData, JSON_PRETTY_PRINT)) === false) {
    die(json_encode(["error" => "Failed to write to output file."]));
}

// Return success response
echo json_encode(["message" => "Data saved successfully to $outputFile"]);



// Initialize cURL session
$ch = curl_init();

curl_setopt_array($ch, [
  CURLOPT_URL => "https://v3.quantdata.us/api/equities/dark-pool/levels/83712406-28f2-48a9-980c-7ca44b848eb0",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Accept: */*",
    "Authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVhdGVkVGltZSI6MTcyOTYzNjMwNzk0MiwidXNlcklkIjoiMzI2Zjk1MWMtNmYxYS00YTFhLWFhYjgtNDA3MGYwOTNhMzg2IiwiaXNzIjoiUXVhbnQgRGF0YSJ9.eWAFAoUeA-epoaqyCeaClmCyzlVfFiTeHPuInDJoetg",
    "Cookie: _lr_tabs_-alsmmd%2Fquant-data-llc={%22recordingID%22:%226-0194759b-b1a8-71da-b8c2-c1656f77db0e%22%2C%22sessionID%22:0%2C%22lastActivity%22:1737139930619%2C%22hasActivity%22:true}; _lr_hb_-alsmmd%2Fquant-data-llc={%22heartbeat%22:1737139925420}; _lr_uf_-alsmmd=a58b5c04-3cb8-4452-a875-2c31fe30afd9; intercom-device-id-rxw83n6n=951348ee-31fd-4fbe-a434-4094bde828fc; intercom-session-rxw83n6n=cVYwd01CcWl0TVFpc3BDVHM4WjkzcDZtWkF4NHptUHRtTzNTbDZzTVFkeW5CbVJNTGEwcUY0eEdtNy9XcmVHYi0tbjhjbWg4MUxQVzdQVFpvZVB1a3Bpdz09--54f30c7c6578efe7c2683735bb30b666419c972d; _ga_YGJ6CZ21GK=GS1.2.1737122936.9.1.1737123085.0.0.0; _fbp=fb.1.1733874713739.829801824338315547; _ga=GA1.2.451680633.1733874714; _gid=GA1.2.1324405007.1736906048; intercom-id-rxw83n6n=82c7e264-3f6d-4f69-a4c7-c8b938884697; token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVhdGVkVGltZSI6MTcyOTYzNjMwNzk0MiwidXNlcklkIjoiMzI2Zjk1MWMtNmYxYS00YTFhLWFhYjgtNDA3MGYwOTNhMzg2IiwiaXNzIjoiUXVhbnQgRGF0YSJ9.eWAFAoUeA-epoaqyCeaClmCyzlVfFiTeHPuInDJoetg; intercom-device-id-rxw83n6n=8af57751-47e8-4a31-8bb4-a693af9795ad",
    "User-Agent: Thunder Client (https://www.thunderclient.com)"
  ],
]);

// Execute the request
$marketDataResponse = curl_exec($ch);
if (curl_errno($ch) || !$marketDataResponse) {
    die(json_encode(["error" => "Failed to fetch market data."]));
}

curl_close($ch);

$marketData = json_decode($marketDataResponse, true);
if (empty($marketData) || !is_array($marketData)) {
    die(json_encode(["error" => "Invalid market data response."]));
}

// Save the transformed data to JSON
$outputFile = 'levelsDQ.json';
if (file_put_contents($outputFile, json_encode($marketData, JSON_PRETTY_PRINT)) === false) {
    die(json_encode(["error" => "Failed to write to output file."]));
}

// Return success response
echo json_encode(["message" => "Data saved successfully to $outputFile"]);



// Initialize cURL session
$ch = curl_init();

curl_setopt_array($ch, [
  CURLOPT_URL => "https://v3.quantdata.us/api/equities/dark-pool/levels/554bc68f-9d22-40e1-9483-95eaba0ec97a",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Accept: */*",
    "Authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVhdGVkVGltZSI6MTcyOTYzNjMwNzk0MiwidXNlcklkIjoiMzI2Zjk1MWMtNmYxYS00YTFhLWFhYjgtNDA3MGYwOTNhMzg2IiwiaXNzIjoiUXVhbnQgRGF0YSJ9.eWAFAoUeA-epoaqyCeaClmCyzlVfFiTeHPuInDJoetg",
    "Cookie: _lr_tabs_-alsmmd%2Fquant-data-llc={%22recordingID%22:%226-0194759b-b1a8-71da-b8c2-c1656f77db0e%22%2C%22sessionID%22:0%2C%22lastActivity%22:1737139930619%2C%22hasActivity%22:true}; _lr_hb_-alsmmd%2Fquant-data-llc={%22heartbeat%22:1737139925420}; _lr_uf_-alsmmd=a58b5c04-3cb8-4452-a875-2c31fe30afd9; intercom-device-id-rxw83n6n=951348ee-31fd-4fbe-a434-4094bde828fc; intercom-session-rxw83n6n=cVYwd01CcWl0TVFpc3BDVHM4WjkzcDZtWkF4NHptUHRtTzNTbDZzTVFkeW5CbVJNTGEwcUY0eEdtNy9XcmVHYi0tbjhjbWg4MUxQVzdQVFpvZVB1a3Bpdz09--54f30c7c6578efe7c2683735bb30b666419c972d; _ga_YGJ6CZ21GK=GS1.2.1737122936.9.1.1737123085.0.0.0; _fbp=fb.1.1733874713739.829801824338315547; _ga=GA1.2.451680633.1733874714; _gid=GA1.2.1324405007.1736906048; intercom-id-rxw83n6n=82c7e264-3f6d-4f69-a4c7-c8b938884697; token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVhdGVkVGltZSI6MTcyOTYzNjMwNzk0MiwidXNlcklkIjoiMzI2Zjk1MWMtNmYxYS00YTFhLWFhYjgtNDA3MGYwOTNhMzg2IiwiaXNzIjoiUXVhbnQgRGF0YSJ9.eWAFAoUeA-epoaqyCeaClmCyzlVfFiTeHPuInDJoetg; intercom-device-id-rxw83n6n=8af57751-47e8-4a31-8bb4-a693af9795ad",
    "User-Agent: Thunder Client (https://www.thunderclient.com)"
  ],
]);

// Execute the request
$marketDataResponse = curl_exec($ch);
if (curl_errno($ch) || !$marketDataResponse) {
    die(json_encode(["error" => "Failed to fetch market data."]));
}

curl_close($ch);

$marketData = json_decode($marketDataResponse, true);
if (empty($marketData) || !is_array($marketData)) {
    die(json_encode(["error" => "Invalid market data response."]));
}

// Save the transformed data to JSON
$outputFile = 'levelsMQ.json';
if (file_put_contents($outputFile, json_encode($marketData, JSON_PRETTY_PRINT)) === false) {
    die(json_encode(["error" => "Failed to write to output file."]));
}

// Return success response
echo json_encode(["message" => "Data saved successfully to $outputFile"]);

?>
