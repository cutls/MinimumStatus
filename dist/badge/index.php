<?php
    ini_set('display_errors', 0);
    $website=$_GET["site"];
    $data=json_decode(file_get_contents("../".$website.'.json'));
    $totalup=$data->success;
    $total=$data->total;
    $todaytotal=date("ymd")."_total";
    $todaysucess=date("ymd")."_success";
    $todayup=$data->$todaysucess;
    $today=$data->$todaytotal;
    if(round($todayup/$today*100,2)>50){
        $color="4c1";
    }else{
        $color="e05d44";
    }
    header("Content-Type: image/svg+xml");
    echo file_get_contents("https://flat.badgen.net/badge/up-time/".urlencode(round($todayup/$today*100,2)."%")."/".$color);
?>
