<?php

function fetchTickers(array $apiUrls) {
    $allTickers = [];

    foreach ($apiUrls as $url) {
        // Get the API response
        $response = file_get_contents($url);

        // Check if the response is valid
        if ($response !== false) {
            // Decode the JSON response to an associative array
            $data = json_decode($response, true);

            // Check if 'tickers' array exists in the response
            if (isset($data['tickers']) && is_array($data['tickers'])) {
                // Merge the tickers into the allTickers array
                $allTickers = array_merge($allTickers, $data['tickers']);
            }
        } else {
            echo "Error fetching data from: $url\n";
        }
    }

    // Remove duplicates by converting to an associative array and back to a regular array
    $uniqueTickers = array_values(array_unique($allTickers));

    return $uniqueTickers;
}

// Example usage with your 9 API URLs
$apiUrls = [
    "https://testapi.webepex.com/ghostboard/scanners/tickers/daytickers.php",
    "https://testapi.webepex.com/ghostboard/scanners/tickers/hammerdtickers.php",
    "https://testapi.webepex.com/ghostboard/scanners/tickers/threeonedaytickers.php",
    "https://testapi.webepex.com/ghostboard/scanners/tickers/wdtickers.php",
    "https://testapi.webepex.com/ghostboard/scanners/tickers/whtickers.php",
    "https://testapi.webepex.com/ghostboard/scanners/tickers/wttickers.php",
    "https://testapi.webepex.com/ghostboard/scanners/tickers/mdtickers.php",
    "https://testapi.webepex.com/ghostboard/scanners/tickers/mhtickers.php",
    "https://testapi.webepex.com/ghostboard/scanners/tickers/mttickers.php",
];

$uniqueTickers = fetchTickers($apiUrls);

// Output the combined unique tickers
    header('Content-Type: application/json');
echo json_encode(["tickers" => $uniqueTickers], JSON_PRETTY_PRINT);

?>
