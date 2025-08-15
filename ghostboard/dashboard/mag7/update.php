<?php
header('Content-Type: application/json'); // Set response type to JSON

// Define the file path
$tickersFile = 'tickers.json';

// Function to send a JSON response
function sendResponse($success, $message) {
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

// Check if the necessary parameters are provided
if (!isset($_GET['ticker']) || !isset($_GET['action'])) {
    sendResponse(false, "Error: Both 'ticker' and 'action' parameters are required.");
}

$ticker = strtoupper(trim($_GET['ticker'])); // Convert ticker to uppercase and trim spaces
$action = strtolower(trim($_GET['action'])); // Convert action to lowercase

// Validate action
if (!in_array($action, ['add', 'delete'])) {
    sendResponse(false, "Error: Action must be either 'add' or 'delete'.");
}

// Check if the tickers file exists
if (!file_exists($tickersFile)) {
    // Create the file if it doesn't exist
    file_put_contents($tickersFile, json_encode([]));
}

// Read the current tickers from the JSON file
$tickers = json_decode(file_get_contents($tickersFile), true);

// Ensure the JSON file contains a valid array
if (!is_array($tickers)) {
    sendResponse(false, "Error: Invalid data in tickers.json.");
}

// Handle the add or delete action
if ($action === 'add') {
    if (in_array($ticker, $tickers)) {
        sendResponse(false, "Error: Ticker '$ticker' already exists.");
    }
    $tickers[] = $ticker; // Add the ticker
    $tickers = array_values(array_unique($tickers)); // Remove duplicates if any
    if (file_put_contents($tickersFile, json_encode($tickers, JSON_PRETTY_PRINT)) !== false) {
        sendResponse(true, "Ticker '$ticker' has been added.");
    } else {
        sendResponse(false, "Error: Failed to update tickers.json.");
    }
} elseif ($action === 'delete') {
    if (!in_array($ticker, $tickers)) {
        sendResponse(false, "Error: Ticker '$ticker' does not exist.");
    }
    $tickers = array_filter($tickers, fn($t) => $t !== $ticker); // Remove the ticker
    $tickers = array_values($tickers); // Reindex the array
    if (file_put_contents($tickersFile, json_encode($tickers, JSON_PRETTY_PRINT)) !== false) {
        sendResponse(true, "Ticker '$ticker' has been deleted.");
    } else {
        sendResponse(false, "Error: Failed to update tickers.json.");
    }
}
?>
