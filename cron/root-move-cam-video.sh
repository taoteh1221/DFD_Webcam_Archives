
#Run this cron job as root (admin)

#Website directory, clearing old files before mirroring over new ones
rm -rf /home/YOUR_USERNAME_HERE/public_html/seccam_video/WebCams

#Copying from video directory to website directory
\cp -r /home/YOUR_USERNAME_HERE/Videos/WebCams /home/YOUR_USERNAME_HERE/public_html/seccam_video

#Set proper filesytem permissions for website application access
chown YOUR_USERNAME_HERE:YOUR_USERNAME_HERE /home/YOUR_USERNAME_HERE/public_html/seccam_video -R
