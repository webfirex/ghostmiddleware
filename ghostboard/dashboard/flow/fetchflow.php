<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Also log to a file in the same directory for debugging
$logFile = __DIR__ . '/write_error.log';
function logError($msg) {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - $msg\n", FILE_APPEND);
}

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

$marketDataApiUrl = 'https://api.ghostboard.net/v1/flow?page=1&pageSize=170000&PUT&CALL&YELLOW&WHITE&MAGENTA&A&B&AA&BB&STOCK&ETF&inTheMoney&outTheMoney';

// Initialize cURL
$ch = curl_init($marketDataApiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: PHP',
    'Content-Type: application/json'
]);

// Cookies
$cookies = 'cf_clearance=8OdfY295lvB7VmKm9KQkTakFyhnAdjNO1.bLXYQGOzs-1731469142-1.2.1.1-kR.VMs5VhpW65.u2GmDUDPqGEjIiDcSVOWvbD1kYn5HHhrPIbHLi6War7jKEENIESZ972Mwua5fr48bLI.7B0ZIm6T5lb_HjatIH_cABKlKECuCz1KJ.u8.UnlS9QkHuAQBEa4DywTzGJwPp8ktkDW6HA6s.gqG5.70Qhd8ewjhFRn6U.XMK6oKrqHSY6vfHIo_C0fi1JQPy1YWEQmgDC.7bsfCuM1BCim8lYFWjX.lOQjAxVqyHDo5yvdi9vvyAX2sx9K1R4VYSOMQfP2MILcJEOJwchbyZF5W1FKmC3f9UvGSlJcqk1tOqn.iJVYN2R6279YDM1dvurl0ErWHQ9jXDci_iQqZZ0nEuE6hIuTRWDcZzavmHttWA9BOxq.hhBoLSVQ8nyO9SvPaD85491w; ghostboard=s%3AiVfBT9nENfaB8XbNYiaOHNX7inYDR9ix.34T7Nr8jha%2FxtR9Tmd2Gs3EVYMBQs%2Bln7CrcLDYUoV8';
curl_setopt($ch, CURLOPT_COOKIE, $cookies);

// Execute
$marketDataResponse = curl_exec($ch);
if (curl_errno($ch) || !$marketDataResponse) {
    $err = curl_error($ch);
    logError("cURL error: $err");
    die(json_encode(["error" => "Failed to fetch market data. See write_error.log"]));
}
curl_close($ch);

// Decode JSON
$marketData = json_decode($marketDataResponse, true);
if (empty($marketData) || !is_array($marketData)) {
    logError("Invalid or empty API response. Raw: " . substr($marketDataResponse, 0, 500));
    die(json_encode(["error" => "Invalid market data response. See write_error.log"]));
}

// Save file
$outputFilePath = __DIR__ . '/alldata.json';

// Check write permissions
if (!is_writable(__DIR__)) {
    logError("Directory is not writable: " . __DIR__);
    die(json_encode(["error" => "Directory not writable. See write_error.log"]));
}

$jsonData = json_encode($marketData, JSON_PRETTY_PRINT);
if ($jsonData === false) {
    logError("JSON encode error: " . json_last_error_msg());
    die(json_encode(["error" => "Failed to encode JSON. See write_error.log"]));
}

if (file_put_contents($outputFilePath, $jsonData) === false) {
    logError("Failed to write to file: $outputFilePath");
    die(json_encode(["error" => "Failed to write file. See write_error.log"]));
}

logError("Successfully wrote data to $outputFilePath");
echo json_encode(["message" => "Data saved successfully to alldata.json"]);
