<?php
// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Load tickers
$tickerFile = 'tickers.json';
if (!file_exists($tickerFile)) {
    http_response_code(500);
    echo json_encode(["error" => "tickers.json not found"]);
    exit;
}
$tickers = json_decode(file_get_contents($tickerFile), true);

// API key and endpoint base
$apiKey = 'wRyvg5z5qdd5lqbs4ProV1OMkOFFDUu3';
$baseUrl = 'https://financialmodelingprep.com/api/v3/historical-chart/1min/';

// Time intervals in minutes
$intervals = [
    "5m" => 5,
    "1h" => 60,
    "6h" => 360,
    "12h" => 720
];

$result = [];

foreach ($tickers as $symbol) {
    $url = $baseUrl . urlencode($symbol) . "?apikey=" . $apiKey;
    $json = @file_get_contents($url);

    if ($json === false) continue;

    $data = json_decode($json, true);
    if (!is_array($data) || empty($data)) continue;

    // Sort data by date descending (API already gives descending)
    usort($data, function ($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    $latest = $data[0];
    $closeNow = $latest['close'];
    $open = $latest['open'];
    $high = max(array_column($data, 'high'));
    $low = min(array_column($data, 'low'));
    $volume = array_sum(array_column($data, 'volume'));

    $changes = [];

    foreach ($intervals as $label => $mins) {
        if (count($data) > $mins) {
            $pastPrice = $data[$mins]['close'];
            $changePercent = (($closeNow - $pastPrice) / $pastPrice) * 100;
            $changes[$label] = round($changePercent, 2);
        } else {
            $changes[$label] = null; // Not enough data
        }
    }

    $result[] = array_merge([
        "symbol" => $symbol,
        "open" => $open,
        "close" => $closeNow,
        "high" => $high,
        "low" => $low,
        "volume" => $volume
    ], $changes);
}

// Save results
file_put_contents("stocksdata.json", json_encode($result, JSON_PRETTY_PRINT));

// Output as JSON (optional)
echo json_encode($result);
