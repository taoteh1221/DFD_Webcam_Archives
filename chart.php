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
    
    //var_dump($levels);
    
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
    
    $total_raw_seconds = strval(trim($total_seconds));
    
    $total_seconds = strval(round(trim($total_seconds)));
    
    $total_raw_minutes = ( $total_raw_seconds / 60 );
    
    $total_minutes = round(( $total_raw_seconds / 60 ));
    
    $total_minutes_dec = number_format(( $total_raw_seconds / 60 ), 1, '.', '');
    
    $total_hours = number_format(( $total_raw_minutes / 60 ), 1, '.', '');
    
    $total_hours_exdec = number_format(( $total_raw_minutes / 60 ), 2, '.', '');
    
    
        if ( $total_minutes > 1 && $total_minutes < 9 ) {
        $measure = 'Minutes';
        $length = $total_minutes_dec;
        $scaling = NULL;
        }
        elseif ( $total_minutes > 8 && $total_minutes < 40 ) {
        $measure = 'Minutes';
        $length = $total_minutes;
        $scaling = NULL;
        }
        elseif ( $total_minutes > 120 ) {
        $measure = 'Hours';
        $length = $total_hours;
        $scaling = NULL;
        }
        elseif ( $total_minutes > 39 ) {
        $measure = 'Hours';
        $length = $total_hours_exdec;
        $scaling = NULL;
        }
        elseif ( $total_minutes < 2 ) {
        $measure = 'Seconds';
        $length = $total_seconds;
        $scaling = NULL;
        }
    
    
    
    $levels_array = chart_array($levels_array, $measure);
        
    
    //var_dump($levels_array);


    $plot = new PHPlot(900, 300);
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
    $plot->SetXTitle('Elapsed Time In '.$measure . ' (final noise event detected '.$length.' '.$measure.' into recording)'.( $measure == 'Hours' ? ' 0.05 hours = 3 minutes' : '' ));
    $plot->SetYTitle('Amplitude Level');

    
    $plot->DrawGraph();
    
?>