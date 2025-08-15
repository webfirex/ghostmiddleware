<?php

// Path to stream.json file
$jsonFile = 'stream.json';

// Check if the file exists
if (!file_exists($jsonFile)) {
    die(json_encode(["error" => "JSON file not found"]));
}

// Get current data from stream.json
$jsonData = json_decode(file_get_contents($jsonFile), true);

// Validate query parameters
if (!isset($_GET['stream']) || !isset($_GET['status'])) {
    die(json_encode(["error" => "Missing required parameters: stream & status"]));
}

$stream = $_GET['stream'];
$status = filter_var($_GET['status'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

// Check if the stream exists
if (!array_key_exists($stream, $jsonData)) {
    die(json_encode(["error" => "Stream not found"]));
}

// Update the status of the stream
$jsonData[$stream] = $status;

// Save the updated data back to the file
if (file_put_contents($jsonFile, json_encode($jsonData, JSON_PRETTY_PRINT))) {
    echo json_encode(["success" => "Stream updated successfully", "updated_data" => $jsonData]);
} else {
    echo json_encode(["error" => "Failed to update stream"]);
}

?>
