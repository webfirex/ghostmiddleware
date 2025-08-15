<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Paths to JSON files
$priceFile = __DIR__ . '/tickerPrice.json'; // First JSON file
$dataFile = __DIR__ . '/tickerData.json';   // Second JSON file

try {
    // Check if files exist and are readable
    if (!file_exists($priceFile) || !file_exists($dataFile)) {
        throw new Exception("One or both files not found.");
    }
    if (!is_readable($priceFile) || !is_readable($dataFile)) {
        throw new Exception("One or both files are not readable.");
    }

    // Read JSON files
    $priceJson = file_get_contents($priceFile);
    $dataJson = file_get_contents($dataFile);

    // Decode JSON files into associative arrays
    $priceArray = json_decode($priceJson, true);
    $dataArray = json_decode($dataJson, true);

    // Check for JSON errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error decoding JSON files.");
    }

    // Convert price array into associative array indexed by symbol
    $priceMap = [];
    foreach ($priceArray as $item) {
        $priceMap[$item['symbol']] = $item;
    }

    // Merge the data
    $mergedData = [];
    foreach ($dataArray as $dataItem) {
        $symbol = $dataItem['symbol'];

        if (isset($priceMap[$symbol])) {
            // Merge dataItem with priceMap entry
            $mergedData[] = array_merge($dataItem, $priceMap[$symbol]);
        } else {
            // If no price data, just add the existing dataItem
            $mergedData[] = $dataItem;
        }
    }

    // Return the merged JSON
    echo json_encode($mergedData, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

?>
