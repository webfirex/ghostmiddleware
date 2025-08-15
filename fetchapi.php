<?php

$urls = [
    "http://testapi.webepex.com/ghostboard/netflowdatandx.php",
    "https://testapi.webepex.com/ghostboard/netflowdataspx.php",
    "http://testapi.webepex.com/ghostboard/netflowdataqqq.php",
    "http://testapi.webepex.com/ghostboard/netflowdataspy.php",
    "http://testapi.webepex.com/ghostboard/netflowdatasqqq.php",
    "http://testapi.webepex.com/ghostboard/netflowdatatsla.php",
    "http://testapi.webepex.com/ghostboard/netflowdatameta.php",
    "http://testapi.webepex.com/ghostboard/netflowdataaapl.php",
    "http://testapi.webepex.com/ghostboard/netflowdataamzn.php",
    "http://testapi.webepex.com/ghostboard/netflowdatababa.php",
    "http://testapi.webepex.com/ghostboard/netflowdatanvda.php",
    "http://testapi.webepex.com/ghostboard/netflowdataamd.php",
    "http://testapi.webepex.com/ghostboard/netflowdatamstr.php",
    "http://testapi.webepex.com/ghostboard/netflowdatacoin.php",
    "http://testapi.webepex.com/ghostboard/netflowdatanflx.php",
    "http://testapi.webepex.com/ghostboard/netflowdatacost.php",
    "http://testapi.webepex.com/ghostboard/netflowdataba.php",
    "http://testapi.webepex.com/ghostboard/netflowdataavgo.php",
    "http://testapi.webepex.com/ghostboard/netflowdatapltr.php",
    "http://testapi.webepex.com/ghostboard/netflowdataspot.php",
    "http://testapi.webepex.com/ghostboard/scanners/intraday.php",
    "http://testapi.webepex.com/ghostboard/scanners/hammerday.php",
    "https://testapi.webepex.com/ghostboard/scanners/liveprice.php",
    "https://testapi.webepex.com/ghostboard/scanners/hammerweek.php",
    "https://testapi.webepex.com/ghostboard/scanners/intraweek.php",
    "https://testapi.webepex.com/ghostboard/scanners/hammermonth.php",
    "https://testapi.webepex.com/ghostboard/scanners/intramonth.php",
    "https://testapi.webepex.com/ghostboard/dashboard/flowai/fetchflowai.php",
    "https://testapi.webepex.com/ghostboard/dashboard/flowai/repeatetf.php",
    "https://testapi.webepex.com/ghostboard/dashboard/flowai/hotflow.php",
    "https://testapi.webepex.com/ghostboard/dashboard/flow/fetchflow.php",
    "https://testapi.webepex.com/ghostboard/dashboard/news/fetchNews.php",
    "https://testapi.webepex.com/ghostboard/dashboard/gamma/exp/spx.php",
    "https://testapi.webepex.com/ghostboard/dashboard/gamma/exp/spy.php",
    "https://testapi.webepex.com/ghostboard/dashboard/darkpool/darkprints.php",
    "https://testapi.webepex.com/ghostboard/dashboard/darkpool/darklevels.php",
    "https://testapi.webepex.com/ghostboard/dashboard/darkpool/darkai.php",
    "https://testapi.webepex.com/ghostboard/dashboard/tickerTape/tickerPrices.php",
    "https://testapi.webepex.com/ghostboard/scanners/stockname.php",
    "https://testapi.webepex.com/ghostboard/ecocalendar/ecalendar.php",
    "https://testapi.webepex.com/ghostboard/dashboard/mag7/fetchStocksData.php",
    "https://testapi.webepex.com/ghostboard/dashboard/markettide/index.php"
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
