<?php
// Allow all origins (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight requests
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// Load and merge the JSON files
$nasdaqFile = __DIR__ . "/nasdaq.json";
$nyseFile = __DIR__ . "/nyse.json";

$nasdaqData = file_exists($nasdaqFile) ? json_decode(file_get_contents($nasdaqFile), true) : [];
$nyseData = file_exists($nyseFile) ? json_decode(file_get_contents($nyseFile), true) : [];

$mergedData = array_merge($nasdaqData, $nyseData);

// Get query parameters
$symbol = isset($_GET["symbol"]) ? strtoupper($_GET["symbol"]) : null;
$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
$limit = isset($_GET["limit"]) ? (int) $_GET["limit"] : 50; // Default: 50 per page
$offset = ($page - 1) * $limit;

// Filter by symbol if provided
if ($symbol) {
    $mergedData = array_filter($mergedData, function ($stock) use ($symbol) {
        return strpos(strtoupper($stock["symbol"]), $symbol) !== false;
    });
}

// Paginate results
$totalItems = count($mergedData);
$paginatedData = array_slice($mergedData, $offset, $limit);

// Response
$response = [
    "total" => $totalItems,
    "page" => $page,
    "limit" => $limit,
    "data" => array_values($paginatedData) // Reindex array
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>
