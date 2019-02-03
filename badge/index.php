<?php
    $website=$_GET["site"];
    $data=json_decode(file_get_contents("../".$website.'.json'));
    $totalup=$data->success;
    $total=$data->total;
    $todaytotal=date("ymd")."_total";
    $todaysucess=date("ymd")."_success";
    $todayup=$data->$todaysucess;
    $today=$data->$todaytotal;
    if(round($todayup/$today*100,2)>50){
        $color="#4c1";
    }else{
        $color="#e05d44";
    }
    header("Content-Type: image/svg+xml");
?>

<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="94" height="20"><linearGradient id="b" x2="0" y2="100%"><stop offset="0" stop-color="#bbb" stop-opacity=".1"/><stop offset="1" stop-opacity=".1"/></linearGradient>
<clipPath id="a"><rect width="94" height="20" rx="3" fill="#fff"/></clipPath><g clip-path="url(#a)"><path fill="#555" d="M0 0h49v20H0z"/><path fill="<?php echo $color ?>" d="M49 0h45v20H49z"/><path fill="url(#b)" d="M0 0h94v20H0z"/></g>
<g fill="#fff" text-anchor="middle" font-family="DejaVu Sans,Verdana,Geneva,sans-serif" font-size="110"> <text x="255" y="150" fill="#010101" fill-opacity=".3" transform="scale(.1)" textLength="390">up-time</text><text x="255" y="140" transform="scale(.1)" textLength="390">up-time</text>
<text x="705" y="150" fill="#010101" fill-opacity=".3" transform="scale(.1)" textLength="350"><?php echo round($todayup/$today*100,2)?>%</text><text x="705" y="140" transform="scale(.1)" textLength="350"><?php echo round($todayup/$today*100,2)?>%</text></g> </svg>