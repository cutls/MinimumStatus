<?php
$data = [];
$time = new DateTime();
for($i = 0; $i < 30; $i++) {
    if($i > 0) {
        $time = $time->modify('-1day');
    }
    $format = $time->format('ymd');
    $array = [];
    $tf = [true, false];
    $tta = [0.2, 0.3, 0.35, 0.37, 0.32, 0.38, 0.4, 0];
    for($j = 0; $j < 100; $j++) {
        shuffle($tf);
        shuffle($tta);
        $array[] = [
            "timestamp" => date('c'),
            "available" => $tf[0],
            "msec" => $tta[0]
        ];
    }
    $data[$format] = $array;
}
echo json_encode($data);