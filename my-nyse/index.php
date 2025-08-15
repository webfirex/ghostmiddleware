<?php
// Get IP address
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    return $_SERVER['REMOTE_ADDR'];
}

// Detect basic browser and OS info
function getUserAgentDetails($userAgent) {
    $browser = "Unknown";
    $os = "Unknown";

    if (preg_match('/linux/i', $userAgent)) $os = "Linux";
    elseif (preg_match('/macintosh|mac os x/i', $userAgent)) $os = "Mac";
    elseif (preg_match('/windows|win32/i', $userAgent)) $os = "Windows";

    if (preg_match('/MSIE|Trident/i', $userAgent)) $browser = "Internet Explorer";
    elseif (preg_match('/Edge/i', $userAgent)) $browser = "Edge";
    elseif (preg_match('/OPR|Opera/i', $userAgent)) $browser = "Opera";
    elseif (preg_match('/Chrome/i', $userAgent)) $browser = "Chrome";
    elseif (preg_match('/Safari/i', $userAgent)) $browser = "Safari";
    elseif (preg_match('/Firefox/i', $userAgent)) $browser = "Firefox";

    return [$browser, $os];
}

// Get geo info from ipapi.co
function getGeoData($ip) {
    $url = "https://ipapi.co/{$ip}/json/";
    $response = @file_get_contents($url);
    if ($response === FALSE) return null;

    return json_decode($response, true);
}

// Log file setup
$logFile = __DIR__ . '/visitor_logs.json';
$existingLogs = file_exists($logFile) ? json_decode(file_get_contents($logFile), true) : [];

$ip = getUserIP();
$userAgent = $_SERVER['HTTP_USER_AGENT'];
[$browser, $os] = getUserAgentDetails($userAgent);
$referrer = $_SERVER['HTTP_REFERER'] ?? 'Direct';
$uri = $_SERVER['REQUEST_URI'];
$timestamp = date('Y-m-d H:i:s');

// Fetch geolocation
$geo = getGeoData($ip);

$log = [
    'timestamp'   => $timestamp,
    'ip'          => $ip,
    'browser'     => $browser,
    'os'          => $os,
    'user_agent'  => $userAgent,
    'referrer'    => $referrer,
    'uri'         => $uri,
];

if ($geo) {
    $log['location'] = [
        'city'     => $geo['city'] ?? null,
        'region'   => $geo['region'] ?? null,
        'country'  => $geo['country_name'] ?? null,
        'latitude' => $geo['latitude'] ?? null,
        'longitude'=> $geo['longitude'] ?? null,
        'org'      => $geo['org'] ?? null,
        'postal'   => $geo['postal'] ?? null,
        'timezone' => $geo['timezone'] ?? null,
    ];
}

$existingLogs[] = $log;
file_put_contents($logFile, json_encode($existingLogs, JSON_PRETTY_PRINT));

echo "Loading...";
