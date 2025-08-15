<?php

$jsonFile = 'data.json';

// Load existing JSON data
if (file_exists($jsonFile)) {
    $jsonData = json_decode(file_get_contents($jsonFile), true);
} else {
    $jsonData = [
        'balance' => '0.00',
        'pnl' => '0.00',
        'mvalue' => '0.00',
        'risklvl' => 'SAFE',
        'positions' => []
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_main'])) {
        // Update main data
        $jsonData['balance'] = $_POST['balance'];
        $jsonData['pnl'] = $_POST['pnl'];
        $jsonData['mvalue'] = $_POST['mvalue'];
        $jsonData['risklvl'] = $_POST['risklvl'];
        $jsonData['cad'] = $_POST['cad'];
        $jsonData['usd'] = $_POST['usd'];
    }
    
    if (isset($_POST['add_position'])) {
        // Add a new position
        $jsonData['positions'][] = [
            'img' => $_POST['img'],
            'name' => $_POST['name'],
            'details' => $_POST['details'],
            'mktval' => $_POST['mktval'],
            'qty' => $_POST['qty'],
            'pnl1' => $_POST['pnl1'],
            'pnl2' => $_POST['pnl2']
        ];
    }
    
    if (isset($_POST['delete_position'])) {
        // Delete position
        $index = $_POST['delete_index'];
        unset($jsonData['positions'][$index]);
        $jsonData['positions'] = array_values($jsonData['positions']);
    }
    
    if (isset($_POST['update_position'])) {
        // Update position
        $index = $_POST['edit_index'];
        $jsonData['positions'][$index] = [
            'img' => $_POST['img'],
            'name' => $_POST['name'],
            'details' => $_POST['details'],
            'mktval' => $_POST['mktval'],
            'qty' => $_POST['qty'],
            'pnl1' => $_POST['pnl1'],
            'pnl2' => $_POST['pnl2']
        ];
    }
    
    file_put_contents($jsonFile, json_encode($jsonData, JSON_PRETTY_PRINT));
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit JSON Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-black">
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Edit JSON Data</h2>
        
        <!-- Edit Main Data -->
        <form method="POST" class="bg-white p-4 shadow-md rounded-lg">
            <h3 class="text-lg font-semibold mb-2">Main Data</h3>
            <label>Balance: <input type="text" name="balance" value="<?= $jsonData['balance']; ?>" class="border p-2 w-full"></label><br>
            <label>PNL: <input type="text" name="pnl" value="<?= $jsonData['pnl']; ?>" class="border p-2 w-full"></label><br>
            <label>Market Value: <input type="text" name="mvalue" value="<?= $jsonData['mvalue']; ?>" class="border p-2 w-full"></label><br>
            <label>Risk Level: <input type="text" name="risklvl" value="<?= $jsonData['risklvl']; ?>" class="border p-2 w-full"></label><br>
            <label>CAD: <input type="text" name="cad" value="<?= $jsonData['cad']; ?>" class="border p-2 w-full"></label><br>
            <label>USD: <input type="text" name="usd" value="<?= $jsonData['usd']; ?>" class="border p-2 w-full"></label><br>
            <button type="submit" name="update_main" class="bg-blue-500 text-white p-2 mt-2 rounded">Update</button>
        </form>

        <!-- Positions List -->
        <h3 class="text-lg font-semibold mt-6">Positions</h3>
        <?php foreach ($jsonData['positions'] as $index => $position) : ?>
            <div class="bg-white p-4 shadow-md rounded-lg mt-2">
                <p><strong>Name:</strong> <?= $position['name']; ?></p>
                <p><strong>Market Value:</strong> <?= $position['mktval']; ?></p>
                <p><strong>Quantity:</strong> <?= $position['qty']; ?></p>
                <form method="POST" class="inline-block">
                    <input type="hidden" name="delete_index" value="<?= $index; ?>">
                    <button type="submit" name="delete_position" class="bg-red-500 text-white p-2 rounded">Delete</button>
                </form>
            </div>
        <?php endforeach; ?>

        <!-- Add or Edit Position -->
        <form method="POST" class="bg-white p-4 shadow-md rounded-lg mt-6">
            <h3 class="text-lg font-semibold mb-2">Add Position</h3>
            <input type="hidden" name="edit_index" value="<?= isset($_GET['edit']) ? $_GET['edit'] : ''; ?>">
            <label>Image URL: <input type="text" name="img" class="border p-2 w-full"></label><br>
            <label>Name: <input type="text" name="name" class="border p-2 w-full"></label><br>
            <label>Details: <input type="text" name="details" class="border p-2 w-full"></label><br>
            <label>Market Value: <input type="text" name="mktval" class="border p-2 w-full"></label><br>
            <label>Quantity: <input type="text" name="qty" class="border p-2 w-full"></label><br>
            <label>PNL 1: <input type="text" name="pnl1" class="border p-2 w-full"></label><br>
            <label>PNL 2: <input type="text" name="pnl2" class="border p-2 w-full"></label><br>
            <button type="submit" name="add_position" class="bg-green-500 text-white p-2 mt-2 rounded">Add Position</button>
        </form>
    </div>
</body>
</html>
