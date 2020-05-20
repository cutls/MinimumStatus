<?php
ini_set('display_errors', 0);
require '../util.php';
$website = $_GET["site"];
$data = json_decode(file_get_contents("../data_" . $website . '.json'));
$calced = dataParse($data, true);
$per = $calced[0]['per'];
if ($per == 100) {
    $color = "4c1";
} elseif ($per > 90) {
    $color = "329ea8";
} elseif ($per > 40) {
    $color = "a89a32";
} else {
    $color = "e05d44";
}
if($data->status) {
    $status = 'operating';
} else {
    $status = 'error';
}
header("Content-Type: image/svg+xml");
echo file_get_contents("https://flat.badgen.net/badge/" . urlencode($website) . "/" . urlencode($status) . "/" . $color);
