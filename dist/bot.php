<?php
require "config.php";
$websites = $config["website"];
$today = date("ymd");
foreach ($websites as $site) {
    $domain = $site["domain"];
    $json = 'data_' . $domain . '.json';
    if (!file_exists($json)) {
        $data = ['status' => false];
        $handle = fopen($json, 'w+');
        fwrite($handle, json_encode($data));
        fclose($handle);
    }
    $data = json_decode(file_get_contents($json));
    if ($site["https"]) {
        $https = "s";
    } else {
        $https = "";
    }
    $url = "http" . $https . "://" . $domain;
    $before = microtime(true);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $exec =  curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);
    $after = microtime(true);
    $msec = $after - $before;
    if ($info["http_code"] == 200) {
        $httpStatus = true;
    } else {
        $httpStatus = false;
        $msec = 0;
    }
    $webhook = $site["if_error"];
    if (!$httpStatus && $data->status) {
        if ($webhook && $webhook != "" && $webhook != "Webhook URL") {
            file_get_contents($webhook . "?site=" . $domain . "&mode=error");
        }
    }
    if ($httpStatus && !$data->status) {
        if ($webhook && $webhook != "" && $webhook != "Webhook URL") {
            file_get_contents($webhook . "?site=" . $domain . "&mode=success");
        }
    }
    $array = $data->$today;
    $array[] = ['timestamp' => date('c'), 'available' => $httpStatus, 'msec' => $msec];
    $data->$today = $array;
    $data->status = $httpStatus;
    $handle = fopen($json, 'w+');
    fwrite($handle, json_encode($data));
    fclose($handle);
}
