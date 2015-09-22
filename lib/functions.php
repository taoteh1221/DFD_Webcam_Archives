<?php

//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
function levels_to_array($levels_data) {
    
        while ( preg_match("/\n /i", $levels_data) ) {
        $levels_data = preg_replace("/\n /i", "\n", $levels_data);
        $levels_data = preg_replace("/\n /i", "\n", $levels_data);
        }
    
    $levels_data = strstr($levels_data, "Channels 1");
    $levels_data = substr_replace($levels_data, '', 0, 10);
    
    //var_dump($levels);
    
    $raw_levels_array = explode("\n", $levels_data);
    
        foreach ( $raw_levels_array as $key => $value ) {
        
            if ( trim($value) == '' ) {
            unset($raw_levels_array[$key]);
            }
        
        }
        
    // Reset after unsetting
    $raw_levels_array = array_values($raw_levels_array);

return $raw_levels_array;

}
//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////
function chart_array($levels_array, $measure) {

global $amplitude_minimum;

$last_time = 0;
$last_level = 0;
$chart_spacing = 0;

        foreach ( $levels_array as $key => $value ) {
        
        $time_array = array('');
        
        $value = trim($value);
        
        //echo $value;
        
            while ( preg_match("/  /i", $value) ) {
            $value = preg_replace("/  /i", " ", $value);
            }
    
        $time_array = explode(" ", $value);
        
        //var_dump($time_array);
        
        $raw_seconds_time = strval(trim($time_array[0]));
        
        $seconds_time = round($raw_seconds_time);
        
        $raw_minutes_time = ( $raw_seconds_time / 60 );
        
        $minutes_time = number_format(( $raw_seconds_time / 60 ), 2, '.', '');
                
        //$hours_time = number_format(( $raw_minutes_time / 60 ), 4, '.', '');
                
                
                if ( $measure == 'Minutes' ) {
                $increment = $minutes_time;
                }
                else {
                $increment = $seconds_time;
                }
        
                $parsed_level = strval(trim($time_array[1]));
                
                if ( $increment > $last_time && $parsed_level >= $amplitude_minimum || $last_level < $parsed_level && $parsed_level >= $amplitude_minimum ) {
                
                    if ( $last_level < $parsed_level && $increment == $last_time ) {
                    unset($levels_array[$last_key]);
                    $chart_spacing = $chart_spacing - 1;
                    }
                    
                    $levels_array[$key] = array(
                                    'time' => $increment,
                                    'level' => $parsed_level
                                    );
                    
                    $chart_spacing = $chart_spacing + 1;
                    
                
                $last_key = $key;
                $last_time = $increment;
                $last_level = $parsed_level;
                }
                else {
                unset($levels_array[$key]);
                }
        
        //var_dump($value);
        
        }


// Reset after unsetting
$levels_array = array_values($levels_array);

$return_array = array(
                    $levels_array,
                    $chart_spacing
                    );
    
return $return_array;

}
//////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////


?>