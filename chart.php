<?php

require_once 'lib/phplot/phplot.php';
require_once 'lib/functions.php';

$levels = file_get_contents('WebCams/'.$_GET['levels']);
    
    
    
        while ( preg_match("/\n /i", $levels) ) {
        $levels = preg_replace("/\n /i", "\n", $levels);
        $levels = preg_replace("/\n /i", "\n", $levels);
        }
    
    $levels = strstr($levels, "Channels 1");
    $levels = substr_replace($levels, '', 0, 10);
    
    $levels_array = explode("\n", $levels);
    
        foreach ( $levels_array as $key => $value ) {
        
            if ( trim($value) == '' ) {
            unset($levels_array[$key]);
            }
        
        }
        
    // Reset after unsetting
    $levels_array = array_values($levels_array);
    
    $total_seconds = $levels_array[(sizeof($levels_array) -1)];
    
    $total_seconds = trim($total_seconds);
    
    $total_seconds = preg_replace("/ (.*)/i", "", $total_seconds);
    
    $total_seconds = strval(round(trim($total_seconds)));
    
    $total_minutes = round(( $total_seconds / 60 ));
    
    $total_minutes_dec = number_format(( $total_seconds / 60 ), 1, '.', '');
    
    $total_hours = number_format(( $total_minutes / 60 ), 2, '.', '');
    
    
        if ( $total_minutes > 2 && $total_minutes < 5 ) {
        $measure = 'Minutes';
        $length = $total_minutes_dec;
        $scaling = NULL;
        }
        elseif ( $total_minutes > 4 && $total_minutes < 91 ) {
        $measure = 'Minutes';
        $length = $total_minutes;
        $scaling = NULL;
        }
        elseif ( $total_minutes > 90 ) {
        $measure = 'Hours';
        $length = $total_hours;
        $scaling = NULL;
        }
        elseif ( $total_minutes < 3 ) {
        $measure = 'Seconds';
        $length = $total_seconds;
        $scaling = NULL;
        }
    
    
    
    $levels_array = chart_array($levels_array, $measure, $length);
        
    //var_dump($levels_array);
    


    $plot = new PHPlot(640, 200);
    $plot->SetImageBorderType('plain');
    
    //Turn off axis ticks and labels because they get in the way:
    //$plot->SetYTickLabelPos('none');
    //$plot->SetXTickLabelPos('none');
    $plot->SetYTickPos('none');
    $plot->SetXTickPos('none');
    $plot->SetPlotAreaWorld(0, NULL, $scaling, NULL);

    $plot->SetPlotType('thinbarline');
    # Make the lines wider:
    $plot->SetLineWidths(5);
    $plot->SetDataType('text-data');
    $plot->SetDataValues($levels_array);
    
    # Main plot titles:
    $plot->SetTitle('Noise Levels for '.str_replace(".levels", "", $_GET['levels']));
    $plot->SetXTitle('Elapsed Time In '.$measure . ' (final noise event detected '.$length.' '.$measure.' into recording)');
    $plot->SetYTitle('Amplitude Level');

    
    $plot->DrawGraph();
    
?>