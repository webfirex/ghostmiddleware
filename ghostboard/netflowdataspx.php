<?php

// Allow access from a specific domain (wildcard "*" cannot be used with credentials)
header("Access-Control-Allow-Origin: https://ghostboard.net");
header("Access-Control-Allow-Origin: https://development.ghostboard.net");


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
// header("Access-Control-Allow-Origin: http://localhost:3000");

// Allow credentials (needed for cookies and Authorization headers)
// header("Access-Control-Allow-Credentials: true");

// Allow specific methods
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Allow specific headers
// header("Access-Control-Allow-Headers: Authorization, Content-Type, Accept");

// Handle OPTIONS requests (used in pre-flight requests for CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Initialize cURL request
$curl = curl_init();

curl_setopt_array($curl, [
 CURLOPT_URL => "https://v3.quantdata.us/api/options/net-drift/cfe53441-c3d1-41cd-bd7d-51cf415b1a66", // spx
// CURLOPT_URL => "https://v3.quantdata.us/api/options/net-drift?toolId=53e15425-36aa-4f71-9657-abb1b44c5077", // spy
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Accept: */*",
    "Authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVhdGVkVGltZSI6MTcyOTYzNjMwNzk0MiwidXNlcklkIjoiMzI2Zjk1MWMtNmYxYS00YTFhLWFhYjgtNDA3MGYwOTNhMzg2IiwiaXNzIjoiUXVhbnQgRGF0YSJ9.eWAFAoUeA-epoaqyCeaClmCyzlVfFiTeHPuInDJoetg",
    "Connection: keep-alive",
    "Cookie: token=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjcmVhdGVkVGltZSI6MTcyOTYzNjMwNzk0MiwidXNlcklkIjoiMzI2Zjk1MWMtNmYxYS00YTFhLWFhYjgtNDA3MGYwOTNhMzg2IiwiaXNzIjoiUXVhbnQgRGF0YSJ9.eWAFAoUeA-epoaqyCeaClmCyzlVfFiTeHPuInDJoetg; intercom-device-id-rxw83n6n=8af57751-47e8-4a31-8bb4-a693af9795ad",
    "User-Agent: Thunder Client (https://www.thunderclient.com)"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    // Decode the JSON response
$data = json_decode($response, true);

if ($data && isset($data['response']['netDrift'])) {
    // Loop through each entry in netDrift and convert the timestamp
    foreach ($data['response']['netDrift'] as &$entry) {
        // Convert timestamp (first element in each sub-array) to DateTime and format it
        $timestamp = $entry[0] / 1000; // Convert from milliseconds to seconds
        $dateTime = new DateTime("@$timestamp"); // Initialize with timestamp
        $dateTime->setTimezone(new DateTimeZone('America/New_York')); // Set timezone

        // Format timestamp to hh:mm AM/PM
        $entry[0] = $dateTime->format('h:i A');
    }

	$dataChecker = $data['response']['netDrift'][556][1];
    // Replace original netDrift data with filtered data
	if ($dataChecker) {
     $data['response']['netDrift'] = array_slice($data['response']['netDrift'], 556);

    // Encode the modified data back to JSON
    $modifiedResponse = json_encode($data, JSON_PRETTY_PRINT);

    // Save modified data to netflowdata.json
    if (file_put_contents('netflowdataspx.json', $modifiedResponse)) {
        echo "Data successfully saved to netflowdata.json";
    } else {
        echo "Error saving data to netflowdataspx.json";
    } }
} else {
    echo "Error decoding JSON data";
}

}
