<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// Set the path to your JSON file
$jsonFile = 'user_data.json';

// Check if the JSON file exists; if not, create it with an empty array
if (!file_exists($jsonFile)) {
    file_put_contents($jsonFile, json_encode([]));
}

// Retrieve the existing data
$data = json_decode(file_get_contents($jsonFile), true);

// Get parameters from the URL
$name = isset($_GET['name']) ? $_GET['name'] : null;
$lastLogin = isset($_GET['lastLogin']) ? $_GET['lastLogin'] : null;
$email = isset($_GET['email']) ? $_GET['email'] : null;

// Validate the received data (simple validation)
if ($name && $lastLogin && $email) {
    // Create a new user data array
    $userData = [
        'name' => $name,
        'lastLogin' => $lastLogin,
        'email' => $email,
    ];

    // Append the new user data to the existing data array
    $data[] = $userData;

    // Save the updated data back to the JSON file
    file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT));

    // Return a success response
    echo json_encode(['status' => 'success', 'message' => 'Data saved successfully']);
} else {
    // Return an error response if validation fails
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
}
?>
