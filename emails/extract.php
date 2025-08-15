<?php
// File paths
$jsonFilePath = 'user_data.json';
$txtFilePath = 'emails.txt';

try {
    // Check if JSON file exists
    if (!file_exists($jsonFilePath)) {
        throw new Exception("JSON file not found.");
    }

    // Read JSON file
    $jsonData = file_get_contents($jsonFilePath);
    $users = json_decode($jsonData, true);

    if ($users === null || !is_array($users)) {
        throw new Exception("Invalid JSON format.");
    }

    // Extract unique emails
    $emails = [];
    foreach ($users as $user) {
        if (isset($user['email']) && filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            $emails[] = $user['email'];
        }
    }
    $uniqueEmails = array_unique($emails);

    // Store emails in a text file
    file_put_contents($txtFilePath, implode(PHP_EOL, $uniqueEmails));

    // Clear the JSON array
    file_put_contents($jsonFilePath, '[]');

    // Force download of the text file
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="emails.txt"');
    header('Content-Length: ' . filesize($txtFilePath));
    readfile($txtFilePath);

    // Clear the text file
    file_put_contents($txtFilePath, '');

} catch (Exception $e) {
    // Handle errors
    echo "Error: " . $e->getMessage();
    exit;
}
?>
