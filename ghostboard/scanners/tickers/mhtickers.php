<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
function fetchTickers() {
    $url = 'https://ghostboard.nyc3.cdn.digitaloceanspaces.com/ghostboard/scanner-results/hammermonth.json';

    // Set up headers
    $options = [
        "http" => [
            "method" => "GET",
            "header" => "Accept: application/json\r\n"
        ]
    ];
    $context = stream_context_create($options);

    // Fetch the data
    $response = @file_get_contents($url, false, $context);

    // Check for errors
    if ($response === FALSE) {
        echo json_encode(["error" => "An error occurred while trying to load the resource." . $reponses]);
        return;
    }

    // Remove extraneous quotes, split by newline, and filter
    $cleanedResponse = trim($response, "\"");
    $tickers = array_filter(array_map('trim', explode("\\n", $cleanedResponse)), fn($ticker) => preg_match('/^[A-Za-z]+$/', $ticker));

    // Wrap the tickers in an associative array
    $result = [
        "tickers" => $tickers
    ];

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode($result);
}

// Call the function to output data as JSON
fetchTickers();

?>
