<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
    // Get the current date
    $today = new DateTime('now', new DateTimeZone('UTC'));

    // Calculate the 'from' date as yesterday
    $fromDate = clone $today;
    $fromDate->modify('-1 day');
    $from = $fromDate->format('Y-m-d');

    // Calculate the 'to' date as one month from today
    $toDate = clone $today;
    $toDate->modify('+1 month');
    $to = $toDate->format('Y-m-d');

    // Updated API URL with dynamic date range
    $url = "https://financialmodelingprep.com/api/v3/economic_calendar?from={$from}&to={$to}&apikey=wRyvg5z5qdd5lqbs4ProV1OMkOFFDUu3";

    // Set up headers for the GET request
    $options = [
        "http" => [
            "method" => "GET",
        ]
    ];
    $context = stream_context_create($options);

    // Fetch the data from the API
    $response = @file_get_contents($url, false, $context);

    // Check if the response was successful
    if ($response === FALSE) {
        throw new Exception("Error fetching data from the API.");
    }

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error decoding JSON data.");
    }

    // Filter the results for entries with currency === "USD" and country === "US"
    $filteredData = array_filter($data, function ($item) {
        return isset($item['currency'], $item['country']) && 
               $item['currency'] === "USD" && 
               $item['country'] === "US";
    });

    // Group data by date with formatted date keys
    $groupedData = [];

    foreach ($filteredData as $item) {
        if (isset($item['date'])) {
            // Convert the date to UTC-5
            $dateTime = new DateTime($item['date'], new DateTimeZone('UTC'));
            $dateTime->setTimezone(new DateTimeZone('America/New_York')); // Convert to UTC-5

            // Format the date to "1st November, 2024"
            $formattedDate = $dateTime->format('jS F, Y');

            // Group events by formatted date
            if (!isset($groupedData[$formattedDate])) {
                $groupedData[$formattedDate] = [];
            }
            
            // Add the event to the corresponding date
            $groupedData[$formattedDate][] = $item;
        }
    }

    // Sort the grouped data by date keys in ascending order
    uksort($groupedData, function ($a, $b) {
        $dateA = DateTime::createFromFormat('jS F, Y', $a);
        $dateB = DateTime::createFromFormat('jS F, Y', $b);
        return $dateA <=> $dateB; // Ascending order
    });

    // Sort events within each date group by time in ascending order
    foreach ($groupedData as $date => &$events) {
        usort($events, function ($a, $b) {
            return strcmp($a['date'], $b['date']);
        });
    }

    // Convert grouped data into a list format
    $finalData = [];
    foreach ($groupedData as $date => $events) {
        $finalData[] = [$date => $events];
    }

    // Store the grouped results in a JSON file
    $jsonFilePath = 'ecalendar.json';
    if (file_put_contents($jsonFilePath, json_encode($finalData, JSON_PRETTY_PRINT)) === false) {
        throw new Exception("Error writing grouped data to JSON file.");
    }

    // Output the grouped data as a JSON response
    echo json_encode($finalData);

} catch (Exception $e) {
    // Handle any errors
    echo json_encode(["error" => $e->getMessage()]);
}
?>
