<?php


?><!DOCTYPE html>
<html>
<head>
<title>Night Vision Webcam - Automated Archiving (last 4 days)</title>
<style>
body {
background: #808080;
}
</style>
</head>
<body>
<div align='center'>
    <h1>Night Vision Webcam - Automated Archiving (last 4 days)</h1>
<p><a href='https://github.com/taoteh1221/DFD_Webcam_Archives/releases' target='_blank'>Download The Source Code Here</a></p>
    
<?php

$the_directory_path = "./WebCams";
        
		// Read file list
		if ($dir = @opendir("$the_directory_path")) {
			$files_array = array();
			$the_loop = 0;
		
			while (($file = readdir($dir)) !== false) {
				if ( eregi("(.*).mp4", $file) || eregi("(.*).DISABLEDOFF", $file) ) {
				$files_array[$the_loop] =  $file;
				$the_loop = $the_loop + 1;
				}
			}
		closedir($dir);
                
                //Sort
		sort($files_array); 
		
			$the_loop = 0;
			while ( $files_array[$the_loop] ) {
                            
			$filename = $files_array[$the_loop];

    ?>
<fieldset align='center' style='margin: 15px; padding: 15px; border: 2px solid black; width: 700px; background: white;'>
    <legend style='position: relative; top: -9px; font-weight: bold; background: white; border-top: 2px solid black; border-left: 2px solid black; border-right: 2px solid black;'> <?=str_replace(".mp4", "", $filename)?> </legend>
    
    <p><i style='color: red;'>Only MP4 video is supported in this web application</i>. Alternately you can try downloading the video directly to your computer (links are below) and running it there, <i style='color: red;'>if you hear sound but cannot see video</i>.</p>
    
    <video poster="WebCams/<?=str_replace(".mp4", ".png", $filename)?>" width="640" height="480" preload="none" controls="controls">
    <source src="WebCams/<?=$filename?>" />
    </video>
    
    <br /><br />
    <i style='color: red;'>Decimals used for some recording lengths, minutes rounded for recordings between 5 and 90 minutes long.</i><br />
    <img src='chart.php?levels=<?=str_replace(".mp4", ".levels", $filename)?>' border='0' />
    
    <br /><br />
    
    <b>
    MP4 Video and Audio Download: <a href='WebCams/<?=$filename?>' target='_blank'><?=$filename?></a><br /><br />
    MP3 <i>Audio Only</i> Download (smaller file): <a href='WebCams/<?=str_replace(".mp4", ".mp3", $filename)?>' target='_blank'><?=str_replace(".mp4", ".mp3", $filename)?></a><br /><br />
    (Opposite-click download links, 'Save As' to download)<br /><br />
    </b>

</fieldset>
    <?php

			$the_loop = $the_loop + 1;
			}
		
    /*
    */
    
}


?>
</div>
</body>
</html>