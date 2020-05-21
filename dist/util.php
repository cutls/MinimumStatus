<?php
function dataParse($data, $onlyToday)
{
    $time = new DateTime();
    $thisDayCt = 0;
    $disable = 0;
    $point = 'until';
    $log = [];
    $today = ['all' => 0, 'disable' => 0, 'per' => 0];
    $week = ['all' => 0, 'disable' => 0, 'per' => 0];
    $month = ['all' => 0, 'disable' => 0, 'per' => 0];
    $graph = [];
    for ($i = 0; $i < 30; $i++) {
        if ($i > 0) {
            $time = $time->modify('-1day');
        }
        $format = $time->format('ymd');
        if (!$data->$format) {
            break;
        }
        $theDay = $data->$format;
        $thisDayCt = $thisDayCt + count($theDay);
        foreach ($theDay as $detail) {
            $ts = $detail->timestamp;
            $labels[] = date('H:i (m-d)', strtotime($ts));
            $graph[] = $detail->msec;
            if ($point === 'until') {
                $point = $detail->available;
            } else {
                if ($point && !$detail->available) {
                    //Recovery
                    $rec = ['status' => 'recovery', 'timestamp' => $ts];
                    $recTime = $ts;
                } elseif (!$point && $detail->available) {
                    //Fail
                    $log[] = ['end' => $ts, 'start'=> $recTime];
                }
                $point = $detail->available;
            }
            if (!$detail->available) {
                $disable++;
            }
        }
        if ($i == 0) {
            //Today
            $today = ['all' => $thisDayCt, 'disable' => $disable, 'per' => round(($thisDayCt - $disable) / $thisDayCt * 100, 2)];
             //Today
             for ($one = 0; $one < count($graph); $one++) {
                if ($one % 5 == 0) {
                    $todayLabels[] = $labels[$one];
                    $todayGraph[] = $graph[$one];
                }
            }
            $todayGraphData = json_encode([
                'type' => 'line',
                'data' => [
                    'labels' => $todayLabels,
                    'datasets' => [
                        [
                            'label' => 'Load(sec) thin outed to 1/5',
                            'data' => $todayGraph,
                            'fill' => false,
                            'borderColor' => 'blue',
                            'borderWidth' => 1
                        ]
                    ]
                ]
            ]);
            if($onlyToday) {
                break;
            }
        }
        if ($i == 6) {
            //7Day
            $week = ['all' => $thisDayCt,  'disable' => $disable, 'per' => round(($thisDayCt - $disable) / $thisDayCt * 100, 2)];
            //Ten
            for ($half = 0; $half < count($graph); $half++) {
                if ($half % 50 == 0) {
                    $weekLabels[] = $labels[$half];
                    $weekGraph[] = $graph[$half];
                }
            }
            $weekGraphData = json_encode([
                'type' => 'line',
                'data' => [
                    'labels' => $weekLabels,
                    'datasets' => [
                        [
                            'label' => 'Load(sec) thin outed to 1/50',
                            'data' => $weekGraph,
                            'fill' => false,
                            'borderColor' => 'blue',
                            'borderWidth' => 1
                        ]
                    ]
                ]
            ]);
        }
        if ($i == 29) {
            //Month
            $month = ['all' => $thisDayCt,  'disable' => $disable, 'per' => round(($thisDayCt - $disable) / $thisDayCt * 100, 2)];
            //Ten
            for ($ten = 0; $ten < count($graph); $ten++) {
                if ($ten % 150 == 0) {
                    $monthLabels[] = $labels[$ten];
                    $monthGraph[] = $graph[$ten];
                }
            }
            $monthGraphData = json_encode([
                'type' => 'line',
                'data' => [
                    'labels' => $monthLabels,
                    'datasets' => [
                        [
                            'label' => 'Load(sec) thin outed to 1/150',
                            'data' => $monthGraph,
                            'fill' => false,
                            'borderColor' => 'blue',
                            'borderWidth' => 1
                        ]
                    ]
                ]
            ]);
        }
    }
    return [$today, $week, $month, $log, $todayGraphData, $weekGraphData, $monthGraphData];
}