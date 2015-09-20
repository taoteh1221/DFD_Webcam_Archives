
#Run this cron job as normal user
#Have ffmpeg / lame / sox installed

#Your video directory
cd /home/YOUR_USERNAME_HERE/Videos/WebCams

pkill -9 wget

START_TEXT=$(date +%Y__%B-%d__%I-%M%P -d  "810 minutes ago")
START=$(date +%Y__%m-%d__%H%Mhours -d  "810 minutes ago")

#Convert to timestamped / indexed / compressed mp4
/bin/ffmpeg -threads 64 -i camBedroom.asf \
-vf drawtext="fontfile=/usr/share/fonts/truetype/freefont/FreeSerif.ttf: x=22: y=12: text='Start Time\: $START_TEXT   |   Elapsed\: %{pts \\: hms}': fontcolor=white@0.4: fontsize=21: box=1: boxcolor=0x00000999@0.4: boxborderw=6" \
-vcodec libx264 -f mp4 camBedroom_$START.mp4 > /dev/null 2>&1

#Creat an mp3 only version
/bin/ffmpeg -i camBedroom.asf -codec:a libmp3lame -qscale:a 2 camBedroom_$START.mp3 > /dev/null 2>&1

#Analyze sound levels, and get time stamps for high levels
/usr/bin/sox camBedroom_$START.mp3 camBedroom_$START.dat
/usr/bin/awk '$2 > 0.024 { print }' < camBedroom_$START.dat > camBedroom_$START.levels

#Create thumbnail
/bin/ffmpeg -i camBedroom_$START.mp4 -ss 00:00:05.000 -vframes 1 camBedroom_$START.png > /dev/null 2>&1 &

#Was buggy on large file via cron
#/usr/bin/HandBrakeCLI -i camBedroom.asf -o camBedroom_$NOW.mp4

sleep 2

#Delete or zero out uneeded files
#Locked, could not delete UNTIL AFTER ZEROING OUT THE FILESIZE BY MAKING AN EMPTY FILE -- KEEP THIS LINE!
> camBedroom_$START.dat
rm -f camBedroom_$START.dat
#rm *.asf
