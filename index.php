<?php
    require "config.php";
    $websites=$config["website"];
    require "db.php";
    $todaytotal=date("ymd")."_total";
    $todaysucess=date("ymd")."_success";
?>
<!doctype html>
<html lang="en">
<head>
 <!-- Compiled and minified CSS -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=0.5, maximum-scale=1, user-scalable=yes">
<meta charset="utf-8">
<title><?php echo $config["name"] ?> Statuses</title>
<style>
body{
    padding:10px;
}
.collection{
    width:500px;
    max-width:100%;
}
</style>
</head>
<body>
<div id="statuses">
<h2><?php echo $config["name"] ?> Statuses</h2>
<?php echo $config["description"]; ?>
<h4>Website statuses</h4>
<ul class="collection">
<?php foreach($websites as $website): 
require_once('OpenGraph.php');  
//URLを指定  
$graph = OpenGraph::fetch($website["domain"]);
$image=$graph->image;
$domain=$website["domain"];
$data=json_decode(file_get_contents($domain.'.json'));
$totalup=$data->success;
$total=$data->total;
$todayup=$data->$todaysucess;
$today=$data->$todaytotal;
$status=$data->status;
if($status=="OK"){
	$info="Operating";
	$color="teal";
}else{
	$info="Error";
	$color="red";
}
?>
<li class="collection-item avatar">
    <img src="<?php echo $image?>" alt="" class="circle">
    <span class="title"><?php echo $website["name"] ?></span><img src="https://status.cutls.com/badge/?site=<?php echo $website["domain"] ?>" class="secondary-content">
    <p><span class="<?php echo $color ?>-text"><?php echo $info ?></span><br>Total:<?php echo $totalup?>/<?php echo $total?>(<?php echo round($totalup/$total*100,2)?>%)<br>
    Today:<?php echo $todayup?>/<?php echo $today?>(<?php echo round($todayup/$today*100,2)?>%)<br>
    </p>
</li>
<?php endforeach; ?>
</ul>
<?php if(count($databases)>0): ?>
<h4>Database statuses</h4>
<ul class="collection">
<?php foreach($databases as $db): ?>
<?php
$domain=$db["host"];
$name=$db["name"];
$data=json_decode(file_get_contents($name.'.json'));
$totalup=$data->success;
$total=$data->total;
$todayup=$data->$todaysucess;
$today=$data->$todaytotal;
$status=$data->status;
if($status=="OK"){
	$info="Operating";
	$color="teal";
}else{
	$info="Error";
	$color="red";
}
?>
<li class="collection-item">
    <span class="title"><?php echo $name ?></span><img src="https://status.cutls.com/badge/?site=<?php echo $name ?>" class="secondary-content">
    <p><span class="<?php echo $color ?>-text"><?php echo $info ?></span><br>Total:<?php echo $totalup?>/<?php echo $total?>(<?php echo round($totalup/$total*100,2)?>%)<br>
    Today:<?php echo $todayup?>/<?php echo $today?>(<?php echo round($todayup/$today*100,2)?>%)<br>
    </p>
</li>
<?php endforeach; ?>
</ul>
<? endif ?>
&copy; <?php echo $config["copy"] ?> 2019<br>
<b>Minimal Status</b>:<i>Legacy PHP Non-DB Status Page</i>(Ready for open-source, plaese wait.)<br>
&copy; <a href="https://kirishima.cloud/@Cutls" target="_blank">Cutls P</a>
</div>