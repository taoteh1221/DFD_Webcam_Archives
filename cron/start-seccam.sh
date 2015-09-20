
#Run this cron job as normal user
#Have wget installed

#Your video directory
cd /home/YOUR_USERNAME_HERE/Videos/WebCams

#Use this format to get audio on foscams...add user/pass/ip
nohup wget http://USERNAME_HERE:PASSWORD_HERE@YOUR.IP.ADDRESS.HERE/videostream.asf \
-O camBedroom.asf > /dev/null 2>&1 &
