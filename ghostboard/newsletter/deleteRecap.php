<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// File containing the JSON data in the same directory
$jsonFile = __DIR__ . '/flowrecap.json';

// Check if the file exists
if (!file_exists($jsonFile)) {
    die(json_encode(["status" => "error", "message" => "JSON file not found."]));
}

// Read the existing JSON data
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

// Check if JSON decoding was successful
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    die(json_encode(["status" => "error", "message" => "Invalid JSON format."]));
}

// Get parameters from the GET method
$month = isset($_GET['month']) ? $_GET['month'] : null;
$index = isset($_GET['index']) ? intval($_GET['index']) : null;

// Validate parameters
if ($month && isset($index) && array_key_exists($month, $data)) {
    // Check if the index exists in the specified month's array
    if (isset($data[$month][$index])) {
        // Remove the specified index
        array_splice($data[$month], $index, 1);

        // If the month's array becomes empty, optionally remove the month
        if (empty($data[$month])) {
            unset($data[$month]);
        }

        // Save the updated JSON data back to the file
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode(["status" => "success", "message" => "Entry deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Index does not exist in the specified month."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid parameters or month not found."]);
}

?>