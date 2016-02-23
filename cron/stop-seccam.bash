#!/bin/bash

### START CONFIG #################################################

#https://trac.ffmpeg.org/wiki/CompilationGuide/Ubuntu
#Have ffmpeg (with H.264 and MP4 support) / lame / sox / awk installed
#Make sure ALL /bin/ paths match your system's setup (via "whereis xxxx" command in a terminal)...
#MAY need: "apt-get install libsox-fmt-mp3" for sox MP3 support

#FULL system path to your video recording directory
VIDEO_DIR="/home/YOUR_USERNAME_HERE/path/to/recordings/"

#Video file to process in VIDEO_DIR directory
VIDEO_FILE="webcam_recording.asf"

#Length of video recording you setup, measured in minutes, to set file timestamp BACK that many minutes to match start time
VIDEO_MINUTES="720"

#Get amplitudes (noise levels) larger than this amplitude
LEVEL_ABOVE="0.019999"

# video / audio / both (lowercase)
INDEX_MODE="both"

### END CONFIG #################################################

pkill -9 wget
sleep 2

cd $VIDEO_DIR

NAME="${VIDEO_FILE//.asf}"
NAME=$NAME"_"
START_TIME=$(date +%Y__%B-%d__%I-%M%P -d  "$VIDEO_MINUTES minutes ago")
START_STAMP=$(date +%Y__%m-%d__%H%Mhours -d  "$VIDEO_MINUTES minutes ago")


if [ $INDEX_MODE == "both" ]; then
    
    
    #mp4
    /bin/ffmpeg -threads 16 -i $VIDEO_FILE \
    -vf drawtext="fontfile=/usr/share/fonts/truetype/freefont/FreeSerif.ttf: x=22: y=12: text='Start Time\: $START_TIME   |   Elapsed\: %{pts \\: hms}': fontcolor=white@0.4: fontsize=21: box=1: boxcolor=0x00000999@0.4: boxborderw=6" \
    -acodec mp2 -vcodec libx264 -f mp4 $NAME$START_STAMP.mp4 > /dev/null 2>&1
    
    #mp3
    /bin/ffmpeg -threads 16 -i $NAME$START_STAMP.mp4 $NAME$START_STAMP.mp3 > /dev/null 2>&1
    
    #Levels file
    /usr/bin/sox $NAME$START_STAMP.mp3 $NAME$START_STAMP.dat
    /usr/bin/awk '$2 > '"$LEVEL_ABOVE"' { print }' < $NAME$START_STAMP.dat > $NAME$START_STAMP.levels
    
    #Thumbnail
    /bin/ffmpeg -i $NAME$START_STAMP.mp4 -ss 00:00:05.000 -vframes 1 $NAME$START_STAMP.png > /dev/null 2>&1 &
    
    sleep 2
    
    #Purge
    > $NAME$START_STAMP.dat
    rm -f $NAME$START_STAMP.dat
    > $VIDEO_FILE
    rm -f $VIDEO_FILE


elif [ $INDEX_MODE == "video" ]; then
    
    
    #mp4
    /bin/ffmpeg -threads 16 -i $VIDEO_FILE \
    -vf drawtext="fontfile=/usr/share/fonts/truetype/freefont/FreeSerif.ttf: x=22: y=12: text='Start Time\: $START_TIME   |   Elapsed\: %{pts \\: hms}': fontcolor=white@0.4: fontsize=21: box=1: boxcolor=0x00000999@0.4: boxborderw=6" \
    -acodec mp2 -vcodec libx264 -f mp4 $NAME$START_STAMP.mp4 > /dev/null 2>&1
    
    #Thumbnail
    /bin/ffmpeg -i $NAME$START_STAMP.mp4 -ss 00:00:05.000 -vframes 1 $NAME$START_STAMP.png > /dev/null 2>&1 &

    sleep 2
        
    #Purge
    > $VIDEO_FILE
    rm -f $VIDEO_FILE
    

elif [ $INDEX_MODE == "audio" ]; then
    
    
    #mp3
    /bin/ffmpeg -threads 16 -i $VIDEO_FILE -codec:a libmp3lame -qscale:a 1 $NAME$START_STAMP.mp3 > /dev/null 2>&1
    
    #Levels file
    /usr/bin/sox $NAME$START_STAMP.mp3 $NAME$START_STAMP.dat
    /usr/bin/awk '$2 > '"$LEVEL_ABOVE"' { print }' < $NAME$START_STAMP.dat > $NAME$START_STAMP.levels
    
    sleep 2
    
    #Purge
    > $NAME$START_STAMP.dat
    rm -f $NAME$START_STAMP.dat
    > $VIDEO_FILE
    rm -f $VIDEO_FILE



else
        echo "No indexing method chosen..."

fi
