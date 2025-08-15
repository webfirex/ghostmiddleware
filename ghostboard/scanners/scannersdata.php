<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$dataType = $_GET['type'];
$dataInterval = $_GET['interval'];
$jsonFile = __DIR__ . '/intraday.json'; // Update path as needed

if ($dataType === 'Inside' && $dataInterval === 'Daily') {
$jsonFile = __DIR__ . '/intraday.json'; // Update path as needed
} else if ($dataType === 'Hammer' && $dataInterval === 'Daily') {
$jsonFile = __DIR__ . '/hammerday.json'; // Update path as needed
} else if ($dataType === 'ThreeOne' && $dataInterval === 'Daily') {
$jsonFile = __DIR__ . '/threeoneday.json'; // Update path as needed
} else if ($dataType === 'price' && $dataInterval === 'price') {
$jsonFile = __DIR__ . '/ticker_fmp_last.json'; // Update path as needed
} else if ($dataType === 'name' && $dataInterval === 'name') {
$jsonFile = __DIR__ . '/ticker_name.json'; // Update path as needed
} else if ($dataType === 'Inside' && $dataInterval === 'Weekly') {
$jsonFile = __DIR__ . '/intraweek.json'; // Update path as needed
} else if ($dataType === 'Hammer' && $dataInterval === 'Weekly') {
$jsonFile = __DIR__ . '/hammerweek.json'; // Update path as needed
} else if ($dataType === 'ThreeOne' && $dataInterval === 'Weekly') {
$jsonFile = __DIR__ . '/threeoneweek.json'; // Update path as needed
} else if ($dataType === 'Inside' && $dataInterval === 'Monthly') {
$jsonFile = __DIR__ . '/intramonth.json'; // Update path as needed
} else if ($dataType === 'Hammer' && $dataInterval === 'Monthly') {
$jsonFile = __DIR__ . '/hammermonth.json'; // Update path as needed
} else if ($dataType === 'ThreeOne' && $dataInterval === 'Monthly') {
$jsonFile = __DIR__ . '/threeonemonth.json'; // Update path as needed
}

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

