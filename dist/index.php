<?php
    require "config.php";
    $websites=$config["website"];
    require "db.php";
    if(!$databases){
        $databases = [];
    }
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
textarea{
    width: 500px;
    max-width: 100%;
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
//URLを指定  
$domain=$website["domain"];
$image=$website["image"];
if(file_exists($domain.'.json')){
    $data=json_decode(file_get_contents($domain.'.json'));
}else{

}
$totalup=$data->success;
$total=$data->total;
$todayup=$data->$todaysucess;
if($total===0 || !$total){
    $pertotal="0";
}else{
    $pertotal=round($totalup/$total*100,2);
}
$today=$data->$todaytotal;
if($today===0 || !$today){
    $pertoday="0";
}else{
    $pertoday=round($todayup/$today*100,2);
}
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
    <span class="title"><?php echo $website["name"] ?></span><img src="./badge/?site=<?php echo $website["domain"] ?>" class="secondary-content">
    <p><span class="<?php echo $color ?>-text"><?php echo $info ?></span> <a href="http<?php if($website["https"]){echo "s";}else{echo "s";} ?>://<?php echo $website["domain"] ?>" target="_blank">Go this site</a>
    <br>Total: <?php echo $total-$totalup ?> min(s) down (<?php echo $pertotal ?>%)<br>
    Today: <?php echo $today-$todayup ?> min(s) down (<?php echo $pertoday; ?>%)<br>
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
if($total===0 || !$total){
    $pertotal="0";
}else{
    $pertotal=round($totalup/$total*100,2);
}
$todayup=$data->$todaysucess;
$today=$data->$todaytotal;
if($today===0 || !$today){
    $pertoday="0";
}else{
    $pertoday=round($todayup/$today*100,2);
}
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
    <span class="title"><?php echo $name ?></span><img src="./badge/?site=<?php echo $name ?>" class="secondary-content">
    <p><span class="<?php echo $color ?>-text"><?php echo $info ?></span><br>Total: <?php echo $total-$totalup ?> min(s) down (<?php echo $pertotal ?>%)<br>
    Today: <?php echo $today-$todayup ?> min(s) down (<?php echo $pertoday; ?>%)<br>
    </p>
</li>
<?php endforeach; ?>
</ul>
<? endif ?>
<h5>Error log</h5>
<textarea id="log"><?php if(file_exists("log")){echo file_get_contents("log");} ?></textarea><br>
&copy; <?php echo $config["copy"] ?> 2019<br>
<b>Minimal Status</b>:<i>Legacy PHP Non-DB Status Page</i>(<a href="https://github.com/cutls/MinimumStatus" target="_blank">GitHub</a>)<br>
&copy; <a href="https://cutls.com/@Cutls" target="_blank">Cutls P</a>
</div>
<script>
window.onload = function() {
    var obj = document.getElementById("log");
    obj.scrollTop = obj.scrollHeight;
};
</script>