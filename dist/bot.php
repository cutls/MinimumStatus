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
        $url="http".$https."://".$domain;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $exec =  curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if($info["http_code"]==200){
            $httpStatus=true;
        }else{
            $httpStatus=false;
        }
        if($data->$todaytotal){
            $data->$todaytotal=$data->$todaytotal+1;
        }else{
            $data->$todaytotal=1;
            $data->$todaysucess=0;
        }
        if($httpStatus){
            $data->success=$data->success+1;
            $data->$todaysucess=$data->$todaysucess+1;
            $status="OK";
        }
        if($status=="OK"){
            if($data->status!="OK"){
                $handle = fopen('log', 'a+');
                fwrite($handle, date("c")." Success:(".$domain.")\n");
                fclose($handle);
            }
        	$data->status="OK";
        }else{
            if($data->status!="error"){
                $webhook=$site["if_error"];
                if($webhook && $webhook!="" && $webhook!="Webhook URL"){
                    file_get_contents($webhook."?site=".$domain);
                }
                $handle = fopen('log', 'a+');
                fwrite($handle, date("c")." Error:(".$domain.")\n");
                fclose($handle);
            }
        	$data->status="error";
        }
        $data->total=$data->total+1;
        $handle = fopen($domain.'.json', 'w+');
        fwrite($handle, json_encode($data));
        fclose($handle);
    }
?>