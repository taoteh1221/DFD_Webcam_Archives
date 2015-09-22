<?php

require_once 'config.php';
require_once 'lib/functions.php';
require_once 'lib/phplot/phplot.php';


$app_var = str_replace(".levels", "", $_GET['levels']);
$app_var = str_replace("-", "_", $app_var);


// Reused session data to save on resource usage
//$levels_data = file_get_contents('WebCams/'.$_GET['levels']);
$levels_data = $_SESSION['levels_data_'.$app_var];
$raw_levels_array = levels_to_array($levels_data);
    
$raw_seconds = $raw_levels_array[(sizeof($raw_levels_array) -1)];
$raw_seconds = trim($raw_seconds);
$raw_seconds = preg_replace("/ (.*)/i", "", $raw_seconds);
$raw_seconds = strval(trim($raw_seconds));
    
$total_raw_seconds = $raw_seconds;
$total_raw_minutes = ( $total_raw_seconds / 60 );
    
$total_seconds = round($total_raw_seconds);
$total_minutes = number_format(( $total_raw_seconds / 60 ), 2, '.', '');
//$total_hours = number_format(( $total_raw_minutes / 60 ), 4, '.', '');
    
    
        if ( $total_minutes < 2 ) {
        $measure = 'Seconds';
        $length = $total_seconds;
        $scaling = NULL;
        }
        elseif ( $total_minutes > 1 ) {
        $measure = 'Minutes';
        $length = $total_minutes;
        $scaling = NULL;
        }
    
    
    
    //var_dump($raw_levels_array);
    
    $_SESSION['get_arrays_'.$app_var] = chart_array($raw_levels_array, $measure);
    $get_arrays = $_SESSION['get_arrays_'.$app_var];
    $chart_array = $get_arrays[0];
    $chart_spacing = $get_arrays[1];
    
    
    $chart_width = round( ( $chart_spacing * ( $total_minutes > 9.9999999999 ? 35 : 30 ) ) );

    if ( $chart_width > 900 ) {
    $plot = new PHPlot($chart_width, 275);
    }
    else {
    $plot = new PHPlot(900, 275);
    }
    
    
    $plot->SetImageBorderType('plain');
    
    //Turn off axis ticks and labels because they get in the way:
    //$plot->SetYTickLabelPos('none');
    //$plot->SetXTickLabelPos('none');
    $plot->SetYTickPos('none');
    $plot->SetXTickPos('none');

    $plot->SetPlotType('thinbarline');
    
    # Make the lines wider:
    $plot->SetLineWidths(5);
    
    $plot->SetDataType('text-data');
    
    $plot->SetPlotAreaWorld(0, 0.05, $scaling, NULL);
    
    
    # Main plot titles:
    $plot->SetTitle('Noise Levels for '.str_replace(".levels", "", $_GET['levels']));
    $plot->SetXTitle('Elapsed Time In '.$measure . ' ('.$length.' '.$measure.' of data, displaying amplitudes of '.$amplitude_minimum.' or greater)'.( $measure == 'Hours' ? ' 0.05 hours = 3 minutes' : '' ));
    $plot->SetYTitle('Amplitude Level');
    
    $plot->SetDataValues($chart_array);
    $plot->DrawGraph();
    
    if ( sizeof($chart_array) > 0 ) {
    }
    else {
    }
    
?>