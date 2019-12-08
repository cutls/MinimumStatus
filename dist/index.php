<?php
    require "config.php";
    $websites=$config["website"];
    require "db.php";
    require "logParser.php";
    if(!$databases){
        $databases = [];
    }
    $todaytotal=date("ymd")."_total";
    $todaysucess=date("ymd")."_success";
    if(file_exists("log")){
        $parse = file_get_contents("log");
        $arr = logParse($parse, $_GET);
    }
?>
<!doctype html>
<html lang="en">
<head>
 <!-- Compiled and minified CSS -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=0.5, maximum-scale=1, user-scalable=yes">
<meta charset="utf-8">
<title><?php echo $config["name"] ?> Statuses</title>
<style>
body{
    padding:10px;
}
.collection, textarea, form, button{
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
<?php if($arr): ?>
<h5>Activity log</h5>
<form action="./" method="get">
    <select name="site">
      <option value="" selected>ALL</option>
      <?php foreach($websites as $website): ?>
      <option value="<?php echo $website["domain"] ?>"><?php echo $website["name"] ?></option>
      <?php endforeach ?>
    </select>
    <label>Filter</label>
    <button class="btn waves-effect">Filter</button>
</form>
<ul class="collection">
    <?php foreach($arr as $key => $activity): ?>
    <li class="collection-item avatar">
        <?php if($activity["status"] == 'Error'): ?>
            <i class="material-icons circle red">error</i>
            <p class="red-text">Down</p>
        <? elseif ($activity["status"] == 'Success'): ?>
            <i class="material-icons circle green">check</i>
            <p class="teal-text">Recovery</p>
        <?php endif; ?>
        <span class="title"><?php echo $activity["domain"] ?></span>
        <br><span><?php echo date('Y-m-d H:i:s', strtotime($activity["datetime"])); ?></span>
        <?php if($activity["message"]): ?><p><?php echo $activity["message"] ?></p><? endif ?>
    </li>
    <? if ($activity["status"] == 'Success' && $arr[$key+1]): ?>
        <li class="collection-item">
            Downtime: <?php echo time_diff(strtotime($arr[$key+1]["datetime"]), strtotime($activity["datetime"])); ?>
        </li>
    <?php endif; ?>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
&copy; <?php echo $config["copy"] ?> 2019<br>
<b>Minimal Status</b>:<i>Legacy PHP Non-DB Status Page</i>(<a href="https://github.com/cutls/MinimumStatus" target="_blank">GitHub</a>)<br>
&copy; <a href="https://cutls.com/@Cutls" target="_blank">Cutls P</a>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems);
  });
</script>