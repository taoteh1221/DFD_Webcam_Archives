#!/bin/bash

### START CONFIG #################################################

#Run this cron job as root (admin) IF your web user is different than your video recording user

#Website system username
WEB_USER="YOUR_WEBUSERNAME_HERE"

# FULL system path to your website "videos" directory
SITE_CAM_DIR="/home/YOUR_USERNAME_HERE/public_html/seccam_video/videos/"

# FULL system path to your video recording directory
VIDEO_DIR="/home/YOUR_USERNAME_HERE/path/to/recordings/"

### END CONFIG #################################################

/usr/bin/rsync -a -v $VIDEO_DIR $SITE_CAM_DIR --delete

chown $WEB_USER:$WEB_USER $SITE_CAM_DIR -R
