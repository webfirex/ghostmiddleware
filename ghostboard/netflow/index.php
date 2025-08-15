<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Net Flow</title>
    <script>
        const urls = [
            "https://testapi.webepex.com/ghostboard/netflowdatandx.php",
            "https://testapi.webepex.com/ghostboard/netflowdataspx.php",
            "https://testapi.webepex.com/ghostboard/netflowdataqqq.php",
            "https://testapi.webepex.com/ghostboard/netflowdataspy.php",
            "https://testapi.webepex.com/ghostboard/netflowdatasqqq.php",
            "https://testapi.webepex.com/ghostboard/netflowdatatsla.php",
            "https://testapi.webepex.com/ghostboard/netflowdatameta.php",
            "https://testapi.webepex.com/ghostboard/netflowdataaapl.php",
            "https://testapi.webepex.com/ghostboard/netflowdataamzn.php",
            "https://testapi.webepex.com/ghostboard/netflowdatababa.php",
            "https://testapi.webepex.com/ghostboard/netflowdatanvda.php",
            "https://testapi.webepex.com/ghostboard/netflowdataamd.php",
            "https://testapi.webepex.com/ghostboard/netflowdatamstr.php",
            "https://testapi.webepex.com/ghostboard/netflowdatacost.php",
            "https://testapi.webepex.com/ghostboard/netflowdatacoin.php",
            "https://testapi.webepex.com/ghostboard/netflowdatanflx.php",
            "https://testapi.webepex.com/ghostboard/scanners/intraday.php",
            "https://testapi.webepex.com/ghostboard/scanners/hammerday.php",
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
            "https://testapi.webepex.com/ghostboard/dashboard/tickerTape/tickerPrices.php",
            "https://testapi.webepex.com/ghostboard/scanners/stockname.php",
            "https://testapi.webepex.com/ghostboard/ecocalendar/ecalendar.php",
            "https://testapi.webepex.com/ghostboard/dashboard/markettide/index.php"
        ];

        function fetchUrls() {
            urls.forEach(url => {
                fetch(url).then(response => {
                    console.log(`Fetched: ${url}, Status: ${response.status}`);
                }).catch(error => {
                    console.error(`Error fetching ${url}:`, error);
                });
            });
        }

        fetchUrls()

        setInterval(fetchUrls, 60000);
    </script>
</head>
<body>
    <h1>Module Updated</h1>
</body>
</html>
