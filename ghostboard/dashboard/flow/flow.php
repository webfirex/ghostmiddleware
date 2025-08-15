<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$allowedOrigins = [
    "https://ghostboard.net",
    "https://development.ghostboard.net"
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
}
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$jsonFile = __DIR__ . '/alldata.json';

try {
    // Check if the JSON file exists and is readable
    if (!file_exists($jsonFile)) {
        throw new Exception("File not found at path: " . $jsonFile);
    }

    // Read and decode the JSON file content
    $jsonData = file_get_contents($jsonFile);
    $data = json_decode($jsonData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error decoding JSON data.");
    }

    if (!isset($data['result']) || !is_array($data['result'])) {
        throw new Exception("Invalid JSON format: 'result' key missing.");
    }

    // Get query parameters
    $putFilter = isset($_GET['PUT']);
    $callFilter = isset($_GET['CALL']);
    $yellowFilter = isset($_GET['YELLOW']);
    $whiteFilter = isset($_GET['WHITE']);
    $magentaFilter = isset($_GET['MAGENTA']);
    $aFilter = isset($_GET['A']);
    $aaFilter = isset($_GET['AA']);
    $bFilter = isset($_GET['B']);
    $bbFilter = isset($_GET['BB']);
    $stockFilter = isset($_GET['STOCK']);
    $etfFilter = isset($_GET['ETF']);
    $outMoneyFilter = isset($_GET['outTheMoney']);
    $inMoneyFilter = isset($_GET['inTheMoney']);
	$sweepFilter = isset($_GET['sweepOnly']);
    $symbolFilter = $_GET['symbol'] ?? null;
    $minValueFilter = $_GET['minimumValue'] ?? null;
    $minCValueFilter = $_GET['minimumCValue'] ?? null;
    $maxCValueFilter = $_GET['maximumCValue'] ?? null;
    $strikeFilter = $_GET['strikeValue'] ?? null;

    // Pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) {
        $page = 1;
    }
    $itemsPerPage = 50;
    $offset = ($page - 1) * $itemsPerPage;

    // Apply filters based on query parameters
    if ($putFilter || $callFilter || $yellowFilter || $whiteFilter || $magentaFilter || $symbolFilter || $minValueFilter || $minCValueFilter || $maxCValueFilter || $aFilter || $aaFilter || $bFilter || $bbFilter || $stockFilter || $etfFilter || $outMoneyFilter || $inMoneyFilter || $sweepFilter || $strikeFilter) {
        $data['result'] = array_filter($data['result'], function ($item) use ($putFilter, $callFilter, $yellowFilter, $whiteFilter, $magentaFilter, $symbolFilter, $minValueFilter, $minCValueFilter, $maxCValueFilter, $bbFilter, $bFilter, $aFilter, $aaFilter, $stockFilter, $etfFilter, $outMoneyFilter, $inMoneyFilter, $sweepFilter, $strikeFilter) {
            $cpMatch = true;
            if ($putFilter && $callFilter) {
                $cpMatch = $item['cp'] === 'PUT' || $item['cp'] === 'CALL';
            } elseif ($putFilter) {
                $cpMatch = $item['cp'] === 'PUT';
            } elseif ($callFilter) {
                $cpMatch = $item['cp'] === 'CALL';
            } else {
                $cpMatch = $item['cp'] === '';
            }
			
			$stockTypeMatch = true;
            if ($stockFilter && $etfFilter) {
                $stockTypeMatch = $item['stock_etf'] === 'ETF' || $item['stock_etf'] === 'STOCK';
            } elseif ($stockFilter) {
                $stockTypeMatch = $item['stock_etf'] === 'STOCK';
            } elseif ($etfFilter) {
                $stockTypeMatch = $item['stock_etf'] === 'ETF';
            } else {
                $stockTypeMatch = true;
            }
			
						
			$moneyTypeMatch = true;
            if ($outMoneyFilter && $inMoneyFilter) {
                $moneyTypeMatch = $item['strike'] > $item['spot'] || $item['strike'] < $item['spot'];
            } elseif ($outMoneyFilter) {
				if ($putFilter && !$callFilter) {$moneyTypeMatch = $item['strike'] < $item['spot'];} else {
				$moneyTypeMatch = $item['strike'] > $item['spot'];}
            } elseif ($inMoneyFilter) {
				if ($putFilter && !$callFilter) {$moneyTypeMatch = $item['strike'] > $item['spot'];} else {
                $moneyTypeMatch = $item['strike'] < $item['spot'];}
            } else {
                $moneyTypeMatch = true;
            }

            $detailsWord = $item['color'];
            $detailsMatch = true;
            if ($yellowFilter && $whiteFilter && $magentaFilter) {
                $detailsMatch = $detailsWord === 'YELLOW' || $detailsWord === 'WHITE' || $detailsWord === 'MAGENTA';
            } elseif ($yellowFilter && $whiteFilter) {
                $detailsMatch = $detailsWord === 'YELLOW' || $detailsWord === 'WHITE';
            } elseif ($yellowFilter && $magentaFilter) {
                $detailsMatch = $detailsWord === 'YELLOW' || $detailsWord === 'MAGENTA';
            } elseif ($whiteFilter && $magentaFilter) {
                $detailsMatch = $detailsWord === 'WHITE' || $detailsWord === 'MAGENTA';
            } elseif ($yellowFilter) {
                $detailsMatch = $detailsWord === 'YELLOW';
            } elseif ($whiteFilter) {
                $detailsMatch = $detailsWord === 'WHITE';
            } elseif ($magentaFilter) {
                $detailsMatch = $detailsWord === 'MAGENTA';
            } else {
                $detailsMatch = $detailsWord === '';
            }
			
			$sideWord = $item['side'];
			$sideMatch = true;
            if ($aFilter && $aaFilter && $bFilter && $bbFilter) {
			    $sideMatch = $sideWord === 'A' || $sideWord === 'AA' || $sideWord === 'B' || $sideWord === 'BB';
			} elseif ($aFilter && $aaFilter && $bFilter) {
			    $sideMatch = $sideWord === 'A' || $sideWord === 'AA' || $sideWord === 'B';
			} elseif ($aFilter && $aaFilter && $bbFilter) {
			    $sideMatch = $sideWord === 'A' || $sideWord === 'AA' || $sideWord === 'BB';
			} elseif ($aFilter && $bFilter && $bbFilter) {
			    $sideMatch = $sideWord === 'A' || $sideWord === 'B' || $sideWord === 'BB';
			} elseif ($aaFilter && $bFilter && $bbFilter) {
			    $sideMatch = $sideWord === 'AA' || $sideWord === 'B' || $sideWord === 'BB';
			} elseif ($aFilter && $aaFilter) {
			    $sideMatch = $sideWord === 'A' || $sideWord === 'AA';
			} elseif ($aFilter && $bFilter) {
			    $sideMatch = $sideWord === 'A' || $sideWord === 'B';
			} elseif ($aFilter && $bbFilter) {
			    $sideMatch = $sideWord === 'A' || $sideWord === 'BB';
			} elseif ($aaFilter && $bFilter) {
			    $sideMatch = $sideWord === 'AA' || $sideWord === 'B';
			} elseif ($aaFilter && $bbFilter) {
			    $sideMatch = $sideWord === 'AA' || $sideWord === 'BB';
			} elseif ($bFilter && $bbFilter) {
			    $sideMatch = $sideWord === 'B' || $sideWord === 'BB';
			} elseif ($aFilter) {
			    $sideMatch = $sideWord === 'A';
			} elseif ($aaFilter) {
			    $sideMatch = $sideWord === 'AA';
			} elseif ($bFilter) {
			    $sideMatch = $sideWord === 'B';
			} elseif ($bbFilter) {
			    $sideMatch = $sideWord === 'BB';
			} else {
			    $sideMatch = true; // No filters are active, no match
			}

            $symbolMatch = true;
            if ($symbolFilter && strtolower($symbolFilter) !== 'null') {
                $symbolMatch = $item['symbol'] === strtoupper($symbolFilter);
            }
			
            $sweepMatch = true;
            if ($sweepFilter) {
                $sweepMatch = $item['type'] === 'SWEEP';
            }
			
            $minValueMatch = true;
            if ($minValueFilter && strtolower($minValueFilter) !== 'null') {
                $minValueMatch = $item['premiumRaw'] >= $minValueFilter;
            }
            
            $minCValueMatch = true;
            if ($minCValueFilter && strtolower($minCValueFilter) !== 'null') {
                $minCValueMatch = $item['price'] >= $minCValueFilter;
            }
            
            $maxCValueMatch = true;
            if ($maxCValueFilter && strtolower($maxCValueFilter) !== 'null') {
                $maxCValueMatch = $item['price'] <= $maxCValueFilter;
            }
            
            $strikeMatch = true;
            if ($strikeFilter && strtolower($strikeFilter) !== 'null') {
                $strikeMatch = $item['strike'] === $strikeFilter;
            }

            return $cpMatch && $detailsMatch && $symbolMatch && $minValueMatch && $minCValueMatch && $maxCValueMatch && $sideMatch && $stockTypeMatch && $symbolMatch && $moneyTypeMatch && $sweepMatch && $strikeMatch;
        });

        // Reindex the filtered array
        $data['result'] = array_values($data['result']);
    }

    // Apply pagination
    $totalItems = count($data['result']);
    $totalPages = ceil($totalItems / $itemsPerPage);
    $paginatedData = array_slice($data['result'], $offset, $itemsPerPage);

    // Prepare the response
    $response = [
        'result' => $paginatedData,
        'success' => true,
        'errors' => null,
        'result_info' => [
            'page' => $page,
            'pages' => $totalPages,
            'per_page' => $itemsPerPage,
            'total_count' => $totalItems
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    // Handle any errors
    echo json_encode(["error" => $e->getMessage()]);
}
?>
