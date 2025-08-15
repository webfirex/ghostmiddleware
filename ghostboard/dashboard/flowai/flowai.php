<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$allowedOrigins = [
    "https://ghostboard.net",
    "https://development.ghostboard.net"
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$jsonFile = __DIR__ . '/alldata.json';
$repeatEtfJsonFile = __DIR__ . '/repeatetf.json';
$hotflowJsonFile = __DIR__ . '/hotflow.json';

try {
    // Check if the JSON file exists and is readable
    if (!file_exists($jsonFile)) {
        throw new Exception("File not found at path: " . $jsonFile);
    }

    // Read and decode the JSON file content
    $jsonData = file_get_contents($jsonFile);
    $data = json_decode($jsonData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error decoding JSON data.");
    }

    if (!isset($data['result']) || !is_array($data['result'])) {
        throw new Exception("Invalid JSON format: 'result' key missing.");
    }
	
	$repeatEtfData = [];
    $repeatEtfFilter = isset($_GET['repeatEtf']);
    if ($repeatEtfFilter) {
        if (!file_exists($repeatEtfJsonFile) || !is_readable($repeatEtfJsonFile)) {
            throw new Exception("Repeat ETF data file not found or not readable.");
        }
        $repeatEtfData = json_decode(file_get_contents($repeatEtfJsonFile), true);

        // Check for errors in decoding repeat ETF JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding repeat ETF data JSON.");
        }

        // Merge the main data with repeat ETF data
        $data['result'] = array_merge($data['result'], $repeatEtfData['result']);
    }

    // Get query parameters
    $putFilter = isset($_GET['PUT']);
    $callFilter = isset($_GET['CALL']);
    $heavyFilter = isset($_GET['heavy']);
    $goldenFilter = isset($_GET['golden']);
	$repeatFilter = isset($_GET['repeat']);
	$symbolFilter = $_GET['symbol'] ?? null;

    // Apply filters based on query parameters
    if ($putFilter || $callFilter || $heavyFilter || $goldenFilter || $repeatFilter || $symbolFilter) {
        $data['result'] = array_filter($data['result'], function ($item) use ($putFilter, $callFilter, $heavyFilter, $goldenFilter, $repeatFilter, $symbolFilter) {
            $cpMatch = true;
            if ($putFilter && $callFilter) {
                $cpMatch = $item['cp'] === 'PUT' || $item['cp'] === 'CALL';
            } elseif ($putFilter) {
                $cpMatch = $item['cp'] === 'PUT';
            } elseif ($callFilter) {
                $cpMatch = $item['cp'] === 'CALL';
            } else {
                $cpMatch = $item['cp'] === '';
            }

            $detailsWord = explode(' ', $item['details'])[0];
            $detailsMatch = true;
            if ($heavyFilter && $goldenFilter && $repeatFilter) {
    $detailsMatch = $detailsWord === 'Heavy' || $detailsWord === 'Golden' || $detailsWord === 'Repeat';
} elseif ($heavyFilter && $goldenFilter) {
    $detailsMatch = $detailsWord === 'Heavy' || $detailsWord === 'Golden';
} elseif ($heavyFilter && $repeatFilter) {
    $detailsMatch = $detailsWord === 'Heavy' || $detailsWord === 'Repeat';
} elseif ($goldenFilter && $repeatFilter) {
    $detailsMatch = $detailsWord === 'Golden' || $detailsWord === 'Repeat';
} elseif ($heavyFilter) {
    $detailsMatch = $detailsWord === 'Heavy';
} elseif ($goldenFilter) {
    $detailsMatch = $detailsWord === 'Golden';
} elseif ($repeatFilter) {
    $detailsMatch = $detailsWord === 'Repeat';
} else {
    $detailsMatch = $detailsWord === '';
}
			
            $symbolMatch = true;
if ($symbolFilter && strtolower($symbolFilter) !== 'null') {
    $symbolMatch = $item['symbol'] === strtoupper($symbolFilter);
}
            
            return $cpMatch && $detailsMatch && $symbolMatch;
        });

        // Reindex the filtered array
        $data['result'] = array_values($data['result']);
    }
	
	$hotflowData = [];
    $hotflowFilter = isset($_GET['hotflow']);
    if ($hotflowFilter) {
        if (!file_exists($hotflowJsonFile) || !is_readable($hotflowJsonFile)) {
            throw new Exception("Repeat ETF data file not found or not readable.");
        }
        $hotflowData = json_decode(file_get_contents($hotflowJsonFile), true);

        // Check for errors in decoding repeat ETF JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decoding repeat ETF data JSON.");
        }

        // Merge the main data with repeat ETF data
        $data['result'] = $hotflowData['result'];
    }

    // Return the filtered data as a JSON response
    echo json_encode($data);

} catch (Exception $e) {
    // Handle any errors
    echo json_encode(["error" => $e->getMessage()]);
}
?>
