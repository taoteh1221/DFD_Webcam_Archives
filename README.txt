
Created By: Michael Kilday <mike@dragonfrugal.com>

http://www.dragonfrugal.com/open.source/software/dfdwebcamarchives/

https://github.com/taoteh1221/DFD_Webcam_Archives/releases

===========================================================================================================
INSTALLATION: 
===========================================================================================================

This application requires ffmpeg (with H.264 / MP4 support), lame, sox, awk, and cron jobs on a server stack. Your PHP web server must support GD for graphs, and you may need to increase your php.ini memory_limit to 1024MB or even higher / max_execution_time to 900 or even higher, if you have very large / long video files.

In the 'cron' directory you'll need to copy each file to a non-web directory (so nobody can see your edited cron job code), and make each executable with 'chmod +x filename.here'. Then setup the config for each job at the top inside each file (using an editor like nano in a terminal). Then you can setup each in crontab, with 'crontab -e' from a terminal. Google for crontab examples if need be.

===========================================================================================================