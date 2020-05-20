<?php
require "config.php";
ini_set( 'display_errors', 0);
require "util.php";
$websites = $config["website"];
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=0.5, maximum-scale=1, user-scalable=yes">
    <meta charset="utf-8">
    <title><?php echo $config["name"] ?> Statuses</title>
</head>

<body>
    <div id="statuses">
        <h2><?php echo $config["name"] ?> Statuses</h2>
        <?php echo $config["description"]; ?>
        <h4>Website statuses</h4>
        <div id="cards">
            <?php foreach ($websites as $website) :
                $date = new DateTime();
                $domain = $website["domain"];
                $image = $website["image"];
                if (file_exists('data_' . $domain . '.json')) {
                    $data = json_decode(file_get_contents('data_' . $domain . '.json'));
                }
                $calced = dataParse($data);
                $status = $data->status;
                if ($status) {
                    $info = "Operating";
                    $color = "teal";
                } else {
                    $info = "Error";
                    $color = "red";
                }
                if ($website["https"]) {
                    $go = 'https://' . $website["domain"];
                } else {
                    $go = 'http://' . $website["domain"];
                }
            ?>
                <div class="card">
                    <img src="<?php echo $image ?>" alt="" class="circle card-favicon secondary-content">
                    <div class="card-content">
                        <span class="card-title"><?php echo $website["name"] ?></span>
                        <p>
                            <span class="<?php echo $color ?>-text">
                                <?php echo $info ?>
                            </span>

                        </p>
                        <table>
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Downtime</th>
                                    <th>Availability</th>
                                    <th>Graph</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>today</td>
                                    <td><?php echo $calced[0]['disable'] ?>m</td>
                                    <td><?php echo $calced[0]['per']; ?>%</td>
                                    <td><button class="btn waves-effect graph" data-href="https://quickchart.io/chart?bkg=white&c=<?php echo urlencode($calced[4]) ?>">View</button></td>
                                </tr>
                                <tr>
                                    <td>7days</td>
                                    <td><?php echo $calced[1]['disable'] ?>m</td>
                                    <td><?php echo $calced[1]['per'] ?>%</td>
                                    <td><button class="btn waves-effect graph" data-href="https://quickchart.io/chart?bkg=white&c=<?php echo urlencode($calced[4]) ?>">View</button></td>
                                </tr>
                                <tr>
                                    <td>30days</td>
                                    <td><?php echo $calced[2]['disable'] ?>m</td>
                                    <td><?php echo $calced[2]['per'] ?>%</td>
                                    <td><button class="btn waves-effect graph" data-href="https://quickchart.io/chart?bkg=white&c=<?php echo urlencode($calced[4]) ?>">View</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <details>
                            <summary>Log</summary>
                            <u>Downtime list</u>(max 30 days)<br />
                            <?php foreach($calced[3] as $log): ?>
                                <?php echo date('Y-m-d H:i', strtotime($log['start'])) ?>~<?php echo date('Y-m-d H:i', strtotime($log['end'])) ?><br />
                            <?php endforeach ?>
                        </details>
                    </div>
                    <div class="card-action">
                        <a href="<?php echo $go ?>" target="_blank">Go this site</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <img id="graphView">
        &copy; <?php echo $config["copy"] ?> 2019<br>
        <b>Minimal Status</b>:<i>Legacy PHP Non-DB Status Page</i>(<a href="https://github.com/cutls/MinimumStatus" target="_blank">GitHub</a> v2)
    </div>
    <script>
        var elements = document.getElementsByClassName("graph");
        for (var i = 0; i < elements.length; i++) {
            elements[i].onclick = function() {
                var data = this.getAttribute('data-href');
                document.getElementById('graphView').setAttribute('src', data);
            }
        }
    </script>