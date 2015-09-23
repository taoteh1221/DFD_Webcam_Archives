#!/bin/bash

### START CONFIG #################################################

#Have ffmpeg (with H.264 and MP4 support) / lame / sox installed
#Make sure ALL /bin/ paths match your system's setup (via "whereis xxxx" command in a terminal)...

#FULL system path to your video recording directory
VIDEO_DIR="/home/YOUR_USERNAME_HERE/path/to/recordings/"

#Video file to process in VIDEO_DIR directory
VIDEO_FILE="webcam_recording.asf"

#Length of video recording you setup, measured in minutes, to set file timestamp BACK that many minutes to match start time
VIDEO_MINUTES="750"

#Get amplitudes (noise levels) larger than this amplitude
LEVEL_ABOVE="0.024"

### END CONFIG #################################################

pkill -9 wget
sleep 5
cd $VIDEO_DIR
NAME="${VIDEO_FILE//.asf}"
NAME=$NAME"_"
START_TIME=$(date +%Y__%B-%d__%I-%M%P -d  "$VIDEO_MINUTES minutes ago")
START_STAMP=$(date +%Y__%m-%d__%H%Mhours -d  "$VIDEO_MINUTES minutes ago")

#mp4
/bin/ffmpeg -threads 16 -i $VIDEO_FILE \
-vf drawtext="fontfile=/usr/share/fonts/truetype/freefont/FreeSerif.ttf: x=22: y=12: text='Start Time\: $START_TIME   |   Elapsed\: %{pts \\: hms}': fontcolor=white@0.4: fontsize=21: box=1: boxcolor=0x00000999@0.4: boxborderw=6" \
-vcodec libx264 -f mp4 $NAME$START_STAMP.mp4 > /dev/null 2>&1

#mp3
/bin/ffmpeg -threads 16 -i $NAME$START_STAMP.mp4 -codec:a libmp3lame -qscale:a 2 $NAME$START_STAMP.mp3 > /dev/null 2>&1

#Levels file
/usr/bin/sox $NAME$START_STAMP.mp3 $NAME$START_STAMP.dat
/usr/bin/awk '$2 > $LEVEL_ABOVE { print }' < $NAME$START_STAMP.dat > $NAME$START_STAMP.levels

#Thumbnail
/bin/ffmpeg -i $NAME$START_STAMP.mp4 -ss 00:00:05.000 -vframes 1 $NAME$START_STAMP.png > /dev/null 2>&1 &

sleep 5

#Purge
> $VIDEO_FILE
rm -f $VIDEO_FILE
> $NAME$START_STAMP.dat
rm -f $NAME$START_STAMP.dat
