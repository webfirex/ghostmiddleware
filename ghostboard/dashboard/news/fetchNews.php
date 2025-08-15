<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set headers to allow cross-origin requests if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

$marketDataApiUrl = 'https://app.quantdata.us/api/v1/fetch/news';

// Initialize cURL session
$ch = curl_init($marketDataApiUrl);

curl_setopt_array($ch, [
  CURLOPT_URL => "https://app.quantdata.us/api/v1/fetch/news",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => [
    "Accept: */*",
    "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjcmVhdGlvblRpbWUiOiIyMDI1LTAxLTE1VDAxOjU0OjIyLjYyNzIyNDQxMVoiLCJpc3MiOiJRdWFudCBEYXRhIiwidXNlcklkIjoxOTg5MzE2MH0.puX1PD8s264JyCZSBslShw_SsMeFXc_ey-NGVaesXYM",
    "Cookie: _ga=GA1.3.1268945375.1732129790; _ga_YGJ6CZ21GK=GS1.3.1736906048.5.1.1736906084.0.0.0; _gid=GA1.3.1324405007.1736906048; client-secret=7a783a23-ec33-4554-977d-b996aa64dd95; intercom-device-id-rxw83n6n=951348ee-31fd-4fbe-a434-4094bde828fc; intercom-id-rxw83n6n=82c7e264-3f6d-4f69-a4c7-c8b938884697; intercom-session-rxw83n6n=; _fbp=fb.1.1733874713739.829801824338315547; _ga=GA1.2.451680633.1733874714; _ga_YGJ6CZ21GK=GS1.2.1736906048.5.0.1736906048.0.0.0; _gat_UA-210397953-1=1; _gid=GA1.2.1324405007.1736906048; token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVhdGVkVGltZSI6MTcyOTYzNjMwNzk0MiwidXNlcklkIjoiMzI2Zjk1MWMtNmYxYS00YTFhLWFhYjgtNDA3MGYwOTNhMzg2IiwiaXNzIjoiUXVhbnQgRGF0YSJ9.eWAFAoUeA-epoaqyCeaClmCyzlVfFiTeHPuInDJoetg",
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
$outputFile = 'alldata.json';
if (file_put_contents($outputFile, json_encode($marketData, JSON_PRETTY_PRINT)) === false) {
    die(json_encode(["error" => "Failed to write to output file."]));
}

// Return success response
echo json_encode(["message" => "Data saved successfully to $outputFile"]);

?>
