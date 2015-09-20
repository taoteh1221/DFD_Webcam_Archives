<?php

function chart_array($levels_array, $measure) {

$last_time = 0;

        foreach ( $levels_array as $key => $value ) {
        
        $time_array = array('');
        
        $value = trim($value);
        
        //echo $value;
        
            while ( preg_match("/  /i", $value) ) {
            $value = preg_replace("/  /i", " ", $value);
            }
    
        $time_array = explode(" ", $value);
        
        //var_dump($time_array);
        
        $raw_seconds = strval(trim($time_array[0]));
        
        $seconds = strval(round(trim($time_array[0])));
        
        $raw_minutes = ( $raw_seconds / 60 );
        
        $minutes = round(( $raw_seconds / 60 ));
                
                
                if ( $minutes > 1 && $minutes < 9 ) {
                $minutes = number_format(( $raw_seconds / 60 ), 1, '.', '');
                }
                
                
        $hours = number_format(( $raw_minutes / 60 ), 1, '.', '');
                
                
                if ( $minutes > 39 && $minutes < 121 ) {
                $hours = number_format(( $raw_minutes / 60 ), 2, '.', '');
                }
                
                
                if ( $measure == 'Minutes' ) {
                $increments = $minutes;
                }
                elseif ( $measure == 'Hours' ) {
                $increments = $hours;
                }
                else {
                $increments = $seconds;
                }
        
        
                if ( $increments > $last_time ) {
                
                $levels_array[$key] = array(
                                'time' => $increments,
                                'level' => trim($time_array[1])
                                );
                    
                }
                else {
                unset($levels_array[$key]);
                }
        
        //var_dump($value);
        
        $last_time = $increments;
        }


// Reset after unsetting
$levels_array = array_values($levels_array);
    
return $levels_array;

}

?>