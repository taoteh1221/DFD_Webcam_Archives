<?php

require_once 'config.php';
require_once 'lib/functions.php';

?><!DOCTYPE html>
<html>
<head>
    
<title><?=$title?></title>

<script src="js/jquery.min.js"></script>

<script>
$( document ).ready(function() {
});



    $( window ).load(function() {
    $( "#loading" ).css( "display", "none" );
    });

</script>

<style>
    
body {
background: #808080;
}

.video_hover:hover {
cursor: pointer;
}

.center_content {
margin: 7px;
}

#loading {
font-weight: bold;
color: red;
}

</style>


</head>
<body>

<!-- Wrapper div START -->
<div align='center' style='position: relative;'>
    
    <div align='center' id='loading'> <h1>Loading, please wait...</h1> </div>
    
    <h1><?=$title?></h1>
    
<?php

$the_directory_path = "./media";
        
		// Read file list
		if ($dir = @opendir("$the_directory_path")) {
			$mp3_array = array();
			$mp4_array = array();
			$the_loop = 0;
		
			while (($file = readdir($dir)) !== false) {
				if ( preg_match("/(.*).mp4/i", $file) ) {
				$mp4_array[$the_loop] =  $file;
				$the_loop = $the_loop + 1;
				}
				if ( preg_match("/(.*).mp3/i", $file) ) {
				$mp3_array[$the_loop] =  $file;
				$the_loop = $the_loop + 1;
				}
			}
		closedir($dir);
                
                //Sort
		sort($mp3_array); 
		sort($mp4_array); 
		
			$the_loop = 0;
			
			if ( sizeof ($mp3_array) > 0 ) {
			$file_array = $mp3_array;
			$is_mp3 = 1;
			}
			
			if ( sizeof ($mp4_array) > 0 ) {
			$file_array = $mp4_array;
			$is_mp4 = 1;
			}
			    
			while ( $file_array[$the_loop] ) {
                            
$filename = $file_array[$the_loop];
$filename = str_replace(".mp3", ".blank", $filename);
$filename = str_replace(".mp4", ".blank", $filename);

$no_preset = ( $is_mp3 ? NULL : 1);

$app_var = str_replace(".blank", "", $filename);
$app_var = str_replace("-", "_", $app_var);

    
    if ( $is_mp3 ) {
	
    // Reuse in chart.php to save on resource usage
    $_SESSION['levels_data_'.$app_var] = file_get_contents('media/'.str_replace(".blank", ".levels", $filename));
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
	    }
	    elseif ( $total_minutes > 1 ) {
	    $measure = 'Minutes';
	    $length = $total_minutes;
	    }
	
    
	$_SESSION['get_arrays_'.$app_var] = chart_array($raw_levels_array, $measure, $total_minutes);
	$get_arrays = $_SESSION['get_arrays_'.$app_var];
	$chart_array = $get_arrays[0];
	$chart_spacing = $get_arrays[1];
	
	
	$chart_width = round( ( $chart_spacing * ( $total_minutes > 9.9999999999 ? 41 : 36 ) ) );
     
    
	if ( sizeof($chart_array) > 0 ) {
	}
	else {
	    $no_preset = 1;
	}
	
    }

?>
    
<!-- START <?=$app_var?> -->

<fieldset align='center' style='position: relative; margin: 15px; padding: 15px; border: 2px solid black;  width: 955px; height: auto; background: white;'>
    <legend style='position: relative; top: -9px; font-weight: bold; background: white; border-top: 2px solid black; border-left: 2px solid black; border-right: 2px solid black;'> <?=str_replace(".blank", "", $filename)?> </legend>
    
    <p align='left'><i style='color: red;'>Only MP4 video and MP3 audio are supported in this web application</i>. You can download the media directly to your computer (links are below) and run it there, <i style='color: red;'>if you have trouble seeing video / etc</i>.</p>

    <?php
    if ( $is_mp4 ) {
    ?>
    <div class='center_content' align='center'>
	
	<video class='video_hover' id='video_<?=$app_var?>' poster="media/<?=str_replace(".blank", ".png", $filename)?>" width="640" height="480" preload="none" controls='controls' onclick="vidplay_<?=$app_var?>()">
	<source src="media/<?=str_replace(".blank", ".mp4", $filename)?>" />
	<progress max="100" value="80"></progress>
	</video>
	
    </div>
    
    
    <div class='center_content' align='center'>
    To show additional video controls, hover mouse pointer over video while playing.
    </div>
    
    <?php
    }
    if ( $is_mp3 ) {
    ?>
    
    <div class='center_content' align='center'>
	
	<audio class='' id='audio_<?=$app_var?>' controls='controls'>
	<source src="media/<?=str_replace(".blank", ".mp3", $filename)?>" type="audio/mpeg">
	</audio>
	
    </div>
    
    <?php
    }
    ?>
    
    <div class='center_content' align='center'>
	
	
	Use player controls for: <select id='switch_<?=$app_var?>'>
	    <?php
	    if ( $is_mp4 ) {
	    ?>
	    <option value='video'> Video MP4 </option>
	    <?php
	    }
	    if ( $is_mp3 ) {
	    ?>
	    <option value='audio' selected> Audio MP3 </option>
	    <?php
	    }
	    ?>
	</select>  &nbsp;
	<button id="restart_<?=$app_var?>" onclick="restart_<?=$app_var?>();"> Restart </button>  &nbsp; 
	<button id="rew_<?=$app_var?>" onclick="skip_<?=$app_var?>(-30)"> &lt;&lt;&lt; Skip Back </button> &nbsp; 
	<button id="play_<?=$app_var?>" onclick="vidplay_<?=$app_var?>()"> Play </button> &nbsp;
	<button id="ff_<?=$app_var?>" onclick="fastforward_<?=$app_var?>()"> Fast Forward &gt;&gt; </button> &nbsp; 
	<button id="skip_<?=$app_var?>" onclick="skip_<?=$app_var?>(30)"> Skip Forward &gt;&gt;&gt; </button>
	
    </div>
    
    <div class='center_content' align='center'>
	
	Go to 
	<select id='pos_type_<?=$app_var?>' onchange='
	if ( this.value == "custom" ) {
	document.getElementById("preset_pos_<?=$app_var?>").style.display = "none";
	document.getElementById("custom_pos_<?=$app_var?>").style.display = "inline";
	    
	}
	else {
	document.getElementById("preset_pos_<?=$app_var?>").style.display = "inline";
	document.getElementById("custom_pos_<?=$app_var?>").style.display = "none";
	}
	' style='<?=( $no_preset ? 'display: none;' : '' )?>'>
	    <option value='preset'> Preset </option>
	    <option value='custom' <?=( $no_preset ? 'selected' : '' )?>> Custom </option>
	</select> position <input id='custom_pos_<?=$app_var?>' type='text' size='4' style='<?=( $no_preset ? '' : 'display: none;' )?>' />
	<select id='preset_pos_<?=$app_var?>'>
	<?php
	foreach ( $chart_array as $key => $value ) {
	?>
	<option value='<?=$chart_array[$key]['time']?>'> <?=$chart_array[$key]['time']?> </option>
	<?php
	}
	?>
	</select>
	<select id='scale_<?=$app_var?>'>
	    <option value='seconds' <?=( strtolower($measure) == 'seconds' ? 'selected' : '' )?>> Seconds </option>
	    <option value='minutes' <?=( strtolower($measure) == 'minutes' ? 'selected' : '' )?>> Minutes </option>
	</select>, and skip back <select id="skip_xsec_<?=$app_var?>">
	    <option value='5' selected> 5 </option>
	    <option value='15'> 15 </option>
	    <option value='30'> 30 </option>
	    <option value='45'> 45 </option>
	</select> seconds <button onclick="position_<?=$app_var?>()"> Go </button>
	
    </div>
    
    <?php
    if ( $is_mp3 ) {
    ?>
    <div class='center_content' align='center'>
	
	<span style='color: red;'><?=$length?> <?=strtolower($measure)?> of data, displaying amplitudes of <?=$amplitude_minimum?> or greater. <?=( $chart_width > 900 ? 'This chart <i>has a horizontal scroll bar on the bottom</i> because the chart is wide.' : '' )?> <?=( $no_preset ? '<p><b>This media <i>has no amplitude data of '.$amplitude_minimum.' or higher</i>.</b></p>' : '' )?></span><br />
	<div align='center' style='position: relative; width: 100%; height: auto;'><iframe align='center' style='position: relative;' width='920' height='315' src='iframe.php?levels=<?=str_replace(".blank", ".levels", $filename)?>'></iframe></div>
	
    </div>
    <?php
    }
    ?>
    
    <div class='center_content' align='center'>
	
	<b>
	(Opposite-click download links below, 'Save As' to download)<br /><br />
	<?php
	if ( $is_mp4 ) {
	?>
	MP4 Video and Audio Download: <a href='media/<?=str_replace(".blank", ".mp4", $filename)?>' target='_blank'><?=str_replace(".blank", ".mp4", $filename)?></a><br /><br />
	<?php
	}
	if ( $is_mp3 ) {
	?>
	MP3 <i>Audio Only</i> Download (smaller than MP4): <a href='media/<?=str_replace(".blank", ".mp3", $filename)?>' target='_blank'><?=str_replace(".blank", ".mp3", $filename)?></a><br /><br />
	<?php
	}
	?>
	</b>
    
    </div>

</fieldset>


<script type="text/javascript">

    function vidplay_<?=$app_var?>() {
	
	if ( document.getElementById("switch_<?=$app_var?>").value == 'video' ) {
	var video_<?=$app_var?> = document.getElementById("video_<?=$app_var?>");
	}
	else {
	var video_<?=$app_var?> = document.getElementById("audio_<?=$app_var?>");
	}
	
       
       
       var button_<?=$app_var?> = document.getElementById("play_<?=$app_var?>");
       var ff_<?=$app_var?> = document.getElementById("ff_<?=$app_var?>");
       if (video_<?=$app_var?>.paused) {
	video_<?=$app_var?>.playbackRate = 1.0;
          video_<?=$app_var?>.play();
          button_<?=$app_var?>.textContent = " Pause ";
	      ff_<?=$app_var?>.style.color = "black";
	      ff_<?=$app_var?>.style.fontWeight = "normal";
       } else {
	video_<?=$app_var?>.playbackRate = 1.0;
          video_<?=$app_var?>.pause();
          button_<?=$app_var?>.textContent = " Play ";
	      ff_<?=$app_var?>.style.color = "black";
	      ff_<?=$app_var?>.style.fontWeight = "normal";
       }
    }

    function restart_<?=$app_var?>() {
	
	    var r = confirm("Are you sure you want to restart the media?");
	    
	    if (r == true) {
		
	
	if ( document.getElementById("switch_<?=$app_var?>").value == 'video' ) {
	var video_<?=$app_var?> = document.getElementById("video_<?=$app_var?>");
	}
	else {
	var video_<?=$app_var?> = document.getElementById("audio_<?=$app_var?>");
	}
	
       
	    var ff_<?=$app_var?> = document.getElementById("ff_<?=$app_var?>");
	    video_<?=$app_var?>.playbackRate = 1.0;
	    video_<?=$app_var?>.currentTime = 0;
	      ff_<?=$app_var?>.style.color = "black";
	      ff_<?=$app_var?>.style.fontWeight = "normal";
	
	    }
	    
    }

    function position_<?=$app_var?>() {
	
		
		if ( document.getElementById("pos_type_<?=$app_var?>").value == 'custom' ) {
		pos_<?=$app_var?> = document.getElementById("custom_pos_<?=$app_var?>").value;
		}
		else {
		pos_<?=$app_var?> = document.getElementById("preset_pos_<?=$app_var?>").value;
		}
		
		
	var scale_<?=$app_var?> = document.getElementById("scale_<?=$app_var?>").value;
	    
	var skip_xsec_<?=$app_var?> = document.getElementById("skip_xsec_<?=$app_var?>").value;
	    
	var r = confirm("Are you sure you want go to position "+pos_<?=$app_var?>+" "+scale_<?=$app_var?>+", and skip back "+skip_xsec_<?=$app_var?>+" seconds?");
	    
	    if (r == true) {
	    
	    
		if ( scale_<?=$app_var?> == 'seconds' ) {
		var position_<?=$app_var?> = pos_<?=$app_var?>;
		}
		else if ( scale_<?=$app_var?> == 'minutes' ) {
		var position_<?=$app_var?> = (pos_<?=$app_var?> * 60);
		}
		else if ( scale_<?=$app_var?> == 'hours' ) {
		var position_<?=$app_var?> = (pos_<?=$app_var?> * 3600);
		}
		
		
	
	if ( document.getElementById("switch_<?=$app_var?>").value == 'video' ) {
	var video_<?=$app_var?> = document.getElementById("video_<?=$app_var?>");
	}
	else {
	var video_<?=$app_var?> = document.getElementById("audio_<?=$app_var?>");
	}
	
       
	    var button_<?=$app_var?> = document.getElementById("play_<?=$app_var?>");
	    var ff_<?=$app_var?> = document.getElementById("ff_<?=$app_var?>");
	    video_<?=$app_var?>.playbackRate = 1.0;
	    
	    
	    console.log(pos_<?=$app_var?>);
	    console.log(Math.round(position_<?=$app_var?>));
	    
	    button_<?=$app_var?>.textContent = " Pause ";
	    ff_<?=$app_var?>.style.color = "black";
	    ff_<?=$app_var?>.style.fontWeight = "normal";
	      
	    video_<?=$app_var?>.currentTime = Math.round(position_<?=$app_var?> - skip_xsec_<?=$app_var?>);
	      
	    video_<?=$app_var?>.play();
	
	    }
	    
    }

    function skip_<?=$app_var?>(value) {
	
	
	if ( document.getElementById("switch_<?=$app_var?>").value == 'video' ) {
	var video_<?=$app_var?> = document.getElementById("video_<?=$app_var?>");
	}
	else {
	var video_<?=$app_var?> = document.getElementById("audio_<?=$app_var?>");
	}
	
       
	var ff_<?=$app_var?> = document.getElementById("ff_<?=$app_var?>");
	   
	if (video_<?=$app_var?>.currentTime > 0) {
	    
        video_<?=$app_var?>.currentTime += value;
	
	video_<?=$app_var?>.playbackRate = 1.0;
	      ff_<?=$app_var?>.style.color = "black";
	      ff_<?=$app_var?>.style.fontWeight = "normal";
	}
	
    }
    
    function fastforward_<?=$app_var?>() {
	
	
	if ( document.getElementById("switch_<?=$app_var?>").value == 'video' ) {
	var video_<?=$app_var?> = document.getElementById("video_<?=$app_var?>");
	}
	else {
	var video_<?=$app_var?> = document.getElementById("audio_<?=$app_var?>");
	}
	
       
	var button_<?=$app_var?> = document.getElementById("ff_<?=$app_var?>");
	   
	if (video_<?=$app_var?>.currentTime > 0) {
	    
	   if (video_<?=$app_var?>.playbackRate == 1.0) {
	      button_<?=$app_var?>.style.color = "red";
	      button_<?=$app_var?>.style.fontWeight = "bold";
	      video_<?=$app_var?>.playbackRate = 6.0;
	   }
	   else if (video_<?=$app_var?>.playbackRate == 6.0) {
	      button_<?=$app_var?>.style.color = "black";
	      button_<?=$app_var?>.style.fontWeight = "normal";
	      video_<?=$app_var?>.playbackRate = 1.0;
	   }
       
	}
	
    }
    
    
</script>
    
<!-- END <?=$app_var?> -->


    <?php

			$the_loop = $the_loop + 1;
			}
			
}
?>

<p><a href='https://github.com/taoteh1221/DFD_Webcam_Archives/releases' target='_blank'>Version <?=$version?></a></p>
</div>
<!-- Wrapper div END -->

</body>
</html>