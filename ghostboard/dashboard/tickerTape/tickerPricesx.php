<?php
// Define the file paths

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

$tickersFile = 'tickers.json';
$outputFile = 'tickerData.json';
$outputFile1 = 'tickerPrice.json';

// Read the tickers from the JSON file
$tickers = json_decode(file_get_contents($tickersFile), true);

// Check if the file contains valid data
if (!$tickers || !is_array($tickers)) {
    die("Error: Invalid tickers file.");
}

// Create a comma-separated string of tickers
$tickerString = implode(',', $tickers);

// Define the API key and the base URL
$apiKey = 'wRyvg5z5qdd5lqbs4ProV1OMkOFFDUu3';
$apiUrl = "https://financialmodelingprep.com/api/v3/stock-price-change/$tickerString?apikey=$apiKey";

// Fetch data from the API
$response = file_get_contents($apiUrl);

// Check if the API response is valid
if ($response === false) {
    die("Error: Failed to fetch data from the API.");
}

// Decode the API response to verify it's valid JSON
$responseData = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error: Invalid JSON response from API.");
}

// Save the response to the output JSON file
if (file_put_contents($outputFile, json_encode($responseData, JSON_PRETTY_PRINT)) === false) {
    die("Error: Failed to save data to $outputFile.");
}

echo "Ticker data has been successfully saved to $outputFile.\n";



$apiUrl1 = "https://financialmodelingprep.com/api/v3/quote-short/$tickerString?apikey=$apiKey";

// Fetch data from the API
$response1 = file_get_contents($apiUrl1);

// Check if the API response is valid
if ($response1 === false) {
    die("Error: Failed to fetch data from the API.");
}

// Decode the API response to verify it's valid JSON
$responseData1 = json_decode($response1, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error: Invalid JSON response from API.");
}

// Save the response to the output JSON file
if (file_put_contents($outputFile1, json_encode($responseData1, JSON_PRETTY_PRINT)) === false) {
    die("Error: Failed to save data to $outputFile1.");
}

echo "Ticker data has been successfully saved to $outputFile1.\n";
?>
