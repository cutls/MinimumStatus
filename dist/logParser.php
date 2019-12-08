<?php
function logParse($text, $get){
    //2019-12-08T10:24:03+09:00 Success:(domain.tld)
    $array = explode("\n", $text);
    $array = array_map("trim", $array);
    $array = array_filter($array, "strlen");
    $array = array_values($array);
    $array = array_reverse($array);
    $filtered = $get["site"];
    $parsed = [];
    foreach ($array as $str) {
        $attr = explode(" ", $str);
        $datetime = $attr[0];
        $nkr = $attr[1];
        $status = substr($nkr, 0, 6);
        $domain = "";
        if ($status == "Error:") {
            $status = "Error";
            preg_match('/\(([a-zA-Z0-9-.]+)\)/', $nkr, $m);
            $domain = $m[1];
        } else if ($status == "Succes") {
            $status = "Success";
            preg_match('/\(([a-zA-Z0-9-.]+)\)/', $nkr, $m);
            $domain = $m[1];
        } else {
            $status = "Undefined";
        }
        $message = "";
        if(count($attr) > 1) {
            for($i = 2; $i < count($attr); $i++){
                $message = $message." ".$attr[$i];
            }
            echo $message;
        }
        if(!$filtered || $filtered == $domain){
            $parsed[] = [
                "datetime" => $datetime,
                "status" => $status,
                "domain" => $domain,
                "message" => $message
            ];
        }
    }
    $parsed = array_splice($parsed, 0, 10);
    return $parsed;
}
function time_diff($time_from, $time_to) 
{
    // 日時差を秒数で取得
    $diff = $time_to - $time_from;
    $sec = $diff % 60;
    $min = ($diff-$sec)/60;
    return $min.' min(s) '.$sec.' sec(s)';
}