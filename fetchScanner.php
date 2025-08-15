<?php

$urls = [
    "http://testapi.webepex.com/ghostboard/scanners/x_intraday.php",
    "http://testapi.webepex.com/ghostboard/scanners/x_hammerday.php",
    "https://testapi.webepex.com/ghostboard/scanners/liveprice_x.php",
    "https://testapi.webepex.com/ghostboard/scanners/x_hammerweek.php",
    "https://testapi.webepex.com/ghostboard/scanners/intraweek_x.php",
    "https://testapi.webepex.com/ghostboard/scanners/x_hammermonth.php",
    "https://testapi.webepex.com/ghostboard/scanners/x_intramonth.php",
    "https://testapi.webepex.com/ghostboard/scanners/stockname_x.php"
];

$multiHandle = curl_multi_init();
$curlHandles = [];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_multi_add_handle($multiHandle, $ch);
    $curlHandles[] = $ch;
}

do {
    $status = curl_multi_exec($multiHandle, $active);
} while ($active && $status == CURLM_CALL_MULTI_PERFORM);

while ($active && $status == CURLM_OK) {
    if (curl_multi_select($multiHandle) == -1) {
        usleep(100);
    }
    do {
        $status = curl_multi_exec($multiHandle, $active);
    } while ($status == CURLM_CALL_MULTI_PERFORM);
}

foreach ($curlHandles as $ch) {
    curl_multi_remove_handle($multiHandle, $ch);
    curl_close($ch);
}

curl_multi_close($multiHandle);

?>
