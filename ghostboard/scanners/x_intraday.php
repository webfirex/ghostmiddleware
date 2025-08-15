<?php

// Enable CORS if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Helper function to log messages
function logMessage($message) {
   // file_put_contents('error_log.txt', date("Y-m-d H:i:s") . " - " . $message . "\n", FILE_APPEND);
}

// Function to make parallel cURL requests
function multiCurlGet($urls) {
    $multiHandle = curl_multi_init();
    $curlHandles = [];

    // Set up each cURL request and add to multi handle
    foreach ($urls as $urlKey => $url) {
        $curlHandles[$urlKey] = curl_init();
        curl_setopt($curlHandles[$urlKey], CURLOPT_URL, $url);
        curl_setopt($curlHandles[$urlKey], CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandles[$urlKey], CURLOPT_TIMEOUT, 20);  // Increase timeout if necessary
        curl_setopt($curlHandles[$urlKey], CURLOPT_HTTPHEADER, ["Accept: application/json"]);
        curl_multi_add_handle($multiHandle, $curlHandles[$urlKey]);
    }

    // Execute the requests in parallel
    $isActive = null;
    do {
        curl_multi_exec($multiHandle, $isActive);
        curl_multi_select($multiHandle);
    } while ($isActive);

    // Collect responses
    $responses = [];
    foreach ($curlHandles as $urlKey => $curlHandle) {
        $response = curl_multi_getcontent($curlHandle);
        $error = curl_error($curlHandle);

        if ($error) {
            logMessage("Error fetching {$urls[$urlKey]}: {$error}");
            $responses[$urlKey] = ["error" => "Failed to fetch data for {$urls[$urlKey]}."];
        } else {
            $decoded = json_decode($response, true);
            if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                logMessage("Invalid JSON response from {$urls[$urlKey]}: {$response}");
                $responses[$urlKey] = ["error" => "Invalid JSON response."];
            } else {
                $responses[$urlKey] = $decoded;
            }
        }

        curl_multi_remove_handle($multiHandle, $curlHandle);
        curl_close($curlHandle);
    }

    curl_multi_close($multiHandle);

    return $responses;
}

// Fetch tickers
function fetchTickers() {
    $url = 'https://ghostboard.nyc3.cdn.digitaloceanspaces.com/ghostboard/scanner-results/insideday.json';
    $data = multiCurlGet([$url]);
    $data = $data[0]; // Only one URL, so get the first response

    if (isset($data["error"])) {
        logMessage("Error fetching tickers");
        return $data;
    }

    if (!isset($data["tickers"]) || !is_array($data["tickers"])) {
        logMessage("Invalid ticker data format from daytickers.php");
        return ["error" => "Invalid ticker data format from daytickers.php"];
    }

    return $data["tickers"];
}

// URLs for each API endpoint
// URLs for each API endpoint
function getApiUrls($symbol) {
    return [
        "intraday" => "https://financialmodelingprep.com/api/v3/historical-chart/30min/{$symbol}?apikey=wRyvg5z5qdd5lqbs4ProV1OMkOFFDUu3",
        "priceChange" => "https://financialmodelingprep.com/api/v3/stock-price-change/{$symbol}?apikey=wRyvg5z5qdd5lqbs4ProV1OMkOFFDUu3",
    ];
}

// Main function to fetch all data for each ticker
function fetchDataForTickers($tickers) {
    $allData = [];

    foreach ($tickers as $symbol) {
        logMessage("Fetching data for ticker: {$symbol}");
        $urls = getApiUrls($symbol);
        $responses = multiCurlGet($urls);

        // Log the responses for debugging
        logMessage("Responses for {$symbol}: " . print_r($responses, true));

        // Parse and validate responses
        $intradayData = isset($responses["intraday"]) ? $responses["intraday"] : null;
        $priceChange = isset($responses["priceChange"]) ? $responses["priceChange"] : null;

        // Log the live price response
        logMessage("Live price response for {$symbol}: " . print_r($livePrice, true));

        // Validate the data received
        if (!$intradayData || !isset($intradayData[0]) || 
            !isset($priceChange[0]["1D"])) {
            logMessage("Incomplete data for {$symbol}. Responses: " . print_r($responses, true));
            continue;
        }

        $allData[] = [
            "symbol" => $symbol,
            "date" => $intradayData[0]["date"],
            "open" => $intradayData[13]["open"],
            "close" => $intradayData[0]["close"],
            "high" => max(array_column(array_slice($responses["intraday"], 0, 13), "high")),
            "low" => min(array_column(array_slice($responses["intraday"], 0, 13), "low")),
            "volume" => $intradayData[0]["volume"],
            "1D" => $priceChange[0]["1D"],
        ];
    }

    return $allData;
}

// Fetch tickers and data
$tickers = fetchTickers();
if (isset($tickers["error"])) {
    logMessage("Error fetching tickers");
    echo json_encode($tickers);
    return;
}

// Check if tickers are fetched correctly
if (empty($tickers)) {
    logMessage("No tickers fetched.");
    echo json_encode(["error" => "No tickers fetched."]);
    return;
}

$allData = fetchDataForTickers($tickers);

// Store data in intraday.json
if (empty($allData)) {
    logMessage("No valid data to store.");
} else {
    file_put_contents('intraday.json', json_encode($allData, JSON_PRETTY_PRINT));
    logMessage("Data has been stored in intraday.json.");
}

echo json_encode(["success" => "Data has been stored in intraday.json."]);

?>
