<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$jsonFile = __DIR__ . '/flowrecap.json'; // Update path as needed

try {
    // Check if the JSON file exists and is readable
    if (!file_exists($jsonFile)) {
        throw new Exception("File not found at path: " . $jsonFile);
    }
    if (!is_readable($jsonFile)) {
        throw new Exception("File exists but is not readable: " . $jsonFile);
    }

    // Read the JSON file content
    $jsonData = file_get_contents($jsonFile);

    // Decode and re-encode to ensure valid JSON output
    $data = json_decode($jsonData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error decoding JSON data.");
    }

    // Return the JSON data as a response
    echo json_encode($data);

} catch (Exception $e) {
    // Handle any errors
    echo json_encode(["error" => $e->getMessage()]);
}
?>

