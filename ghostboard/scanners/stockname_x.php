<?php

// Set headers to allow cross-origin requests if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// URLs for the two APIs
$tickersApiUrl = 'https://testapi.webepex.com/ghostboard/scanners/tickers/gettickers.php';
$marketDataApiUrl = 'https://financialmodelingprep.com/api/v3/available-traded/list?apikey=wRyvg5z5qdd5lqbs4ProV1OMkOFFDUu3';

// Fetch data from the first API to get tickers
$tickersResponse = file_get_contents($tickersApiUrl);
if ($tickersResponse === false) {
    die(json_encode(["error" => "Failed to fetch tickers data."]));
}

$tickersData = json_decode($tickersResponse, true);
$tickersArray = $tickersData['tickers'] ?? [];

if (empty($tickersArray)) {
    die(json_encode(["error" => "No tickers found in the response."]));
}

// Fetch data from the second API to get market data
$marketDataResponse = file_get_contents($marketDataApiUrl);
if ($marketDataResponse === false) {
    die(json_encode(["error" => "Failed to fetch market data."]));
}

$marketData = json_decode($marketDataResponse, true);
if (empty($marketData) || !is_array($marketData)) {
    die(json_encode(["error" => "Invalid market data response."]));
}

// Prepare the output array to store tickers with their fmpLast values
$tickerFmpLast = [];

// Loop through the tickers and find matching market data
foreach ($tickersArray as $ticker) {
    foreach ($marketData as $data) {
        if ($data['symbol'] === $ticker) {
            $tickerFmpLast[$ticker] = $data['name'];
            break;  // Stop searching once the ticker is found
        }
    }
}

// Convert the result to JSON and save to a file
$outputFile = 'ticker_name.json';
if (file_put_contents($outputFile, json_encode($tickerFmpLast, JSON_PRETTY_PRINT)) === false) {
    die(json_encode(["error" => "Failed to write to output file."]));
}

// Return success response
echo json_encode(["message" => "Data saved successfully to $outputFile"]);

?>
