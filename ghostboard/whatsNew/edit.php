<?php
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');


// Path to your JSON file
$jsonFile = __DIR__ . '/data.json';

// Load current data
if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, '[]'); // Create an empty JSON file if it doesn't exist
}
$data = json_decode(file_get_contents($jsonFile), true);

// Handle the request
$action = $_GET['action'] ?? null;

if ($action === 'add') {
    $image = $_GET['image'] ?? null;
    $description = $_GET['description'] ?? null;

    if ($image && $description) {
        $data[] = [
            'description' => $description,
            'image' => $image
        ];
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        echo "Added successfully.";
    } else {
        echo "Error: 'image' and 'description' are required for adding.";
    }

} elseif ($action === 'delete') {
    $index = isset($_GET['index']) ? (int)$_GET['index'] : -1;

    if ($index >= 0 && $index < count($data)) {
        array_splice($data, $index, 1);
        file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        echo "Deleted successfully.";
    } else {
        echo "Error: Invalid index.";
    }

} else {
    echo "Error: Invalid or missing 'action'.";
}
?>
