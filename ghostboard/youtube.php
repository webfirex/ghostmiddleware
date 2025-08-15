<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
function fetchTickers() {
    $url = 'https://www.googleapis.com/youtube/v3/search?key=AIzaSyCymprJS5eXal579fSVGISmea0oVrTuyDo&channelId=UC6FNYa7BpuhpMrZ7dWWaMfQ&part=snippet&order=date&maxResults=100';

    // Set up headers
    $options = [
        "http" => [
            "method" => "GET",
        ]
    ];
    $context = stream_context_create($options);

    // Fetch the data
    $response = @file_get_contents($url);

    // Check for errors
    if ($response === FALSE) {
        echo json_encode(["error" => "An error occurred while trying to load the resource."]);
        return;
    }

    // Return the data as JSON
    header('Content-Type: application/json');
    echo $response;
}

// Call the function to output data as JSON
fetchTickers();

?>
