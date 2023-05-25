<?php

if (file_exists('config.php')) {
    include 'config.php';
}

$uri = $_SERVER['REQUEST_URI'];
$server_name = $_SERVER['SERVER_NAME'];
$queryString = $_SERVER['QUERY_STRING'];
parse_str($queryString, $params);
$domainname = str_replace('www.', '', $server_name);
$lnaddress = $params['lnaddress'];

if (!preg_match('/^[a-z0-9._-]+$/', $lnaddress) || empty($lnaddress) || strlen($lnaddress) > 76) {
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid lnaddress format.';
    exit;
}

$prefix = "walletanotester-";
$lnaddres_without_tester = (strpos($lnaddress, $prefix) === 0) ? substr($lnaddress, strlen($prefix)) : $lnaddress;

if (empty($allowed_lnaddresses) || !isset($allowed_lnaddresses) || in_array($lnaddress, $allowed_lnaddresses) || in_array($lnaddres_without_tester, $allowed_lnaddresses)) {
    $curl_url = "https://www.walletano.com/domainlnaddress/".$lnaddress."@".$domainname;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $curl_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    }
    curl_close($ch);

    header('Content-Type: application/json');
    echo $output;
} else {
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid lnaddress.';
}
?>