<?php

function chart_array($levels_array, $measure, $length) {

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
        
        $seconds = strval(round(trim($time_array[0])));
                
                
                if ( $length > 2 && $length < 5 ) {
                $minutes = number_format(( $seconds / 60 ), 1, '.', '');
                }
                elseif ( $length > 4 && $length < 91 ) {
                $minutes = round(( $seconds / 60 ));
                }
                
                
        $hours = number_format(( $minutes / 60 ), 2, '.', '');
                
                
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