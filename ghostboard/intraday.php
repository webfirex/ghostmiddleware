<?php

// Enable CORS if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Helper function to make a cURL request
function curlGet($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return [
            "error" => "An error occurred while trying to load the resource.",
            "details" => $error,
        ];
    }

    // Decode JSON response
    $decoded = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ["error" => "Invalid JSON format from API"];
    }

    return $decoded;
}

// Class to hold intraday data
class IntradayData {
    public $date;
    public $open;
    public $close;
    public $high;
    public $low;
    public $volume;

    public function __construct($date, $open, $close, $high, $low, $volume) {
        $this->date = $date;
        $this->open = $open;
        $this->close = $close;
        $this->high = $high;
        $this->low = $low;
        $this->volume = $volume;
    }
}

// Function to fetch the latest intraday data for the given symbol
function fetchIntradayData($symbol) {
    $url = "https://financialmodelingprep.com/api/v3/historical-chart/30min/{$symbol}?apikey=wRyvg5z5qdd5lqbs4ProV1OMkOFFDUu3";

    $data = curlGet($url);
    if (isset($data["error"]) || !isset($data[0])) {
        return (object)["error" => "No intraday data found for {$symbol}."];
    }

    // Get the first entry
    $firstEntry = $data[0];
    $firstDate = $firstEntry["date"];

    // Initialize variables for the calculation
    $open = $data[12]["open"];
    $high = max($data[12]["high"],$data[11]["high"], $data[10]["high"], $data[9]["high"],$data[8]["high"],$data[7]["high"],$data[6]["high"],$data[5]["high"],$data[4]["high"],$data[3]["high"],$data[2]["high"],$data[1]["high"], $data[0]["high"]);  // Start with the lowest possible float value
    $low = min($data[12]["low"],$data[11]["low"], $data[10]["low"], $data[9]["low"],$data[8]["low"],$data[7]["low"],$data[6]["low"],$data[5]["low"],$data[4]["low"],$data[3]["low"],$data[2]["low"],$data[1]["low"], $data[0]["low"]);    // Start with the highest possible float value
    $volume = $firstEntry["volume"];
    $close = $firstEntry["close"];

    // Loop through the data to find entries with the same date
  //  foreach ($data as $entry) {
    //    if ($entry["date"] === $firstDate) {
            // Set open to the farthest index's open value with the same date
     //       if ($open === null) {
  //              $open = $entry["open"]; // Initialize on the first match
    //        }
    //        // Update high and low
  //          if ($entry["high"] > $high) {
//                $high = $entry["high"];
        //    }
      //      if ($entry["low"] < $low) {
    //            $low = $entry["low"];
    //        }
    //   }
    // }

    // If no entries matched the date, return an error
    if ($open === null) {
        return (object)["error" => "No data found for date: {$firstDate}."];
    }

    // Construct and return an instance of IntradayData
    return new IntradayData($firstDate, $open, $close, $high, $low, $volume);
}

// Function to fetch the 1-day price change for the given symbol
function fetchOneDayPriceChange($symbol) {
    $url = "https://financialmodelingprep.com/api/v3/stock-price-change/{$symbol}?apikey=wRyvg5z5qdd5lqbs4ProV1OMkOFFDUu3";

    $data = curlGet($url);
    if (isset($data["error"]) || !isset($data[0]["1D"])) {
        return ["error" => "No 1-day price change data found for {$symbol}."];
    }

    // Return only the 1D price change value
    return $data[0]["1D"];
}

// Function to fetch the company name for the given symbol
function fetchCompanyName($symbol) {
    $url = "https://financialmodelingprep.com/api/v3/search-ticker?query={$symbol}&limit=1&apikey=wRyvg5z5qdd5lqbs4ProV1OMkOFFDUu3";

    $data = curlGet($url);
    if (isset($data["error"]) || empty($data) || !isset($data[0]["name"])) {
        return ["error" => "No company name found for {$symbol}."];
    }

    // Return only the company name
    return $data[0]["name"];
}

// Function to get tickers from daytickers.php
function fetchTickers() {
    $url = 'https://testapi.webepex.com/ghostboard/daytickers.php';
    $data = curlGet($url);
    if (isset($data["error"])) {
        return $data; // Return error message if there's an issue with the cURL request
    }

    // Check if tickers array is present and valid
    if (!isset($data["tickers"]) || !is_array($data["tickers"])) {
        return ["error" => "Invalid ticker data format from daytickers.php"];
    }

    return $data["tickers"];
}

// Main logic
$tickers = fetchTickers();
if (isset($tickers["error"])) {
    echo json_encode($tickers); // Output error if tickers retrieval failed
    return;
}

$allData = [];
foreach ($tickers as $symbol) {
    // Fetch intraday data
    $intradayData = fetchIntradayData($symbol);
    if (isset($intradayData->error)) {
        continue;
    }

    // Fetch 1D price change
    $priceChange1D = fetchOneDayPriceChange($symbol);
    if (is_array($priceChange1D) && isset($priceChange1D["error"])) {
        continue;
    }

    // Fetch company name
    $companyName = fetchCompanyName($symbol);
    if (is_array($companyName) && isset($companyName["error"])) {
        continue;
    }

    // Add symbol, 1D price change, and company name to intraday data
    $intradayData->symbol = $symbol;
    $intradayData->{"1D"} = $priceChange1D;  // Accessing dynamic property
    $intradayData->name = $companyName;

    // Append to the allData array
    $allData[] = $intradayData;
}

// Store the data in intraday.json
file_put_contents('intraday.json', json_encode($allData, JSON_PRETTY_PRINT));

echo json_encode(["success" => "Data has been stored in intraday.json."]);

?>
