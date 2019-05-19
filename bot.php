<?php
    require "config.php";
    $websites=$config["website"];
    $todaytotal=date("ymd")."_total";
    $todaysucess=date("ymd")."_success";
    foreach($websites as $site){
        $domain=$site["domain"];
    	$status="error";
        if(!file_exists($domain.'.json')){
            $data=["total"=>0,"success"=>0];
            $handle = fopen($domain.'.json', 'w+');
            fwrite($handle, json_encode($data));
            fclose($handle);
        }
        $data=json_decode(file_get_contents($domain.'.json'));
        if($site["https"]){
            $https="s";
        }else{
            $https="";
        }
        echo "http".$https."://".$domain."/ndstatus.json";
        $checkjson=file_get_contents("http".$https."://".$domain."/ndstatus.json");
        if($data->$todaytotal){
            $data->$todaytotal=$data->$todaytotal+1;
        }else{
            $data->$todaytotal=1;
            $data->$todaysucess=0;
        }
        if($checkjson){
            $check=json_decode($checkjson);
            if($check->status=="OK"){
                $data->success=$data->success+1;
                $data->$todaysucess=$data->$todaysucess+1;
                $status="OK";
            }
        }
        if($status=="OK"){
        	$data->status="OK";
        }else{
        	$data->status="error";
        }
        $data->total=$data->total+1;
        $handle = fopen($domain.'.json', 'w+');
        fwrite($handle, json_encode($data));
        fclose($handle);
    }
    require "db.php";
    foreach($databases as $dbdata){
    	$status="error";
        $domain=$dbdata["name"];
        if(!file_exists($domain.'.json')){
            $data=["total"=>0,"success"=>0];
            $handle = fopen($domain.'.json', 'w+');
            fwrite($handle, json_encode($data));
            fclose($handle);
        }
        $data=json_decode(file_get_contents($domain.'.json'));
        $checkjson=checkdb($dbdata);
        if($data->$todaytotal){
            $data->$todaytotal=$data->$todaytotal+1;
        }else{
            $data->$todaytotal=1;
            $data->$todaysucess=0;
        }
        if($checkjson){
            $check=json_decode($checkjson);
            if($check->status=="OK"){
                $data->success=$data->success+1;
                $data->$todaysucess=$data->$todaysucess+1;
                $status="OK";
            }
        }
        if($status=="OK"){
        	$data->status="OK";
        }else{
        	$data->status="error";
        }
        $data->total=$data->total+1;
        $handle = fopen($domain.'.json', 'w+');
        fwrite($handle, json_encode($data));
        fclose($handle);
    }
    function checkdb($dbdata){
    $link = new mysqli($dbdata["host"] , $dbdata["user"] , $dbdata["password"] , $dbdata["name"]);
    if ($link->connect_error){
	    $sql_error = $link->connect_error;
	    return '{"status":"failed"}';
    } else {
	    return '{"status":"OK"}';
    }
    }
?>